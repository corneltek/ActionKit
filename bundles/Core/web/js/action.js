/*
vim:fdm=marker:sw=4:et:

Action.js: Javascript for submitting forms and validations.
Depends on: minilocale.js region.js, jQuery.scrollTo.js
Author: Yo-An Lin <cornelius.howl@gmail.com>
Date: 2/16 17:04:44 2011 

USAGE
-----

$(window).error( function(errorMessage, fileName, lineNumber) {
        alert( fileName + ':' + errorMessage + '  Line:' + lineNumber );
});
*/

window.debug = function( ) {
    if( window.console ) {
        window.console.log.apply(window.console, arguments);
    }
};

var Action;
var ActionHighlight;

ActionHighlight = function(a) { 
    this.action = a;
    this.form = a.form();

    // insert highlight wrappers
    this.action.findFields().each(function(i,e) {
        var el = $(this);
        var name = el.attr('name');
        el.wrap( '<div class="highlight highlight-' + name + '"/>' );
    });
};

ActionHighlight.prototype = { 
    apply: function(fields) {
        var that = this;
        $(fields).each(function(i,field) {
            var name = field.name;
            var type = field.type;
            var el = that.form.find('.highlight-' + name );
            el.addClass("highlight-" + type );
        });
    },
    clear: function() {
        this.form.find('.highlight')
            .removeClass('highlight-error')
            .removeClass('highlight-success');
    }
};

var Logger = function() {
    this.msgs = [ ];
    this.callbacks = [ ];
};
Logger.prototype = {
    hook: function(cb) { 
        this.callbacks.push( cb );
    },
    getMsgs:   function() { return this.msgs; },
    log:   function() { 
        this.msgs.push( arguments ); 
        for ( var i in this.callbacks ) {
            var cb = this.callbacks[ i ];
            cb.call( this, arguments );
        }
    },
    clear: function() { this.msgs = [ ]; }
};

var MessageWindow  = function(opts) {
    this.opts = $.extend( {  } , opts );
    this.el = $('<div/>');
    this.el.css({
        position: 'fixed',
        left: 10,
        bottom: 10,
        height: 120,
        width: 600,
        background: '#fff',
        overflow: 'auto',
        border: "1px solid #ccc",
        borderRadius: 10,
        padding: 6
    });

    var that = this;
    var title =  $('<div/>').css({ height: 20 }).addClass("title").html("Message Window");
    var closeBtn = $('<span/>').addClass("close-btn")
                        .css({ 'float': 'right', borderBottom: "1px solid blue" })
                        .text('close')
                        .click( function() {
                            that.hide();
                        });

    title.append( closeBtn );

    this.el.append( title ); // title

    this.msgEl =  $('<div/>').addClass("messages")
                .css({ overflow: 'auto' , 
                        height: 100,
                        font: "12px Monaco,Courier New" });

    this.el.append( this.msgEl );
    this.el.hide();
    $(document.body).append(this.el);

    this.log( "Starting log ..." );
};


MessageWindow.create = function(opts) {
    if( typeof MsgWindowInstance != "undefined" ) {
        return MsgWindowInstance;
    }
    MsgWindowInstance = new MessageWindow( opts );
    return MsgWindowInstance;
};

MessageWindow.prototype = {
    show: function() { this.el.show(); },

    hide: function() { this.el.hide(); },

    inflateArg: function(arg) { 
        if( typeof arg == "object" || typeof arg == "array" )
            return JSON.stringify( arg );
        return arg;
    },

    log: function() {
        var msg = $('<div/>').addClass('msg').css({ borderBottom: '1px dotted #ccc' });
        var chunks = [ ];
        for( var i=0; i < arguments.length; ++i ) {
            var arg = arguments[i];
            chunks.push( this.inflateArg( arg ) );
        }
        msg.text( chunks.join( ', ') );
        this.msgEl.prepend(msg);
    }
};


var ActionPerfield = function(a) { 
    this.action = a;
    this.form = a.form();

    // create highlight div wrappers 
    // and message divs for fields
    this.action.findFields().each(function() { 
        var field = $(this);


        /* this will get action name , Fix this for action name like: 'Phifty::Action::....' */
        var name = $(this).attr('name');
        var actionId = name.replace( /::/g , '-' )

        // append message div
        var m = $('.field-' + actionId + '-message').hide();
        if( ! m.get(0) )
            field.after( '<div class="action-field-message field-' + actionId + '-message"/>' );

        // wrap with highlight div
        var w = $('.field-' + name);
        if( ! w.get(0) )
            field.wrap( '<div class="action-field field-'+ actionId +'"/>' );
    });
};
ActionPerfield.prototype = { 
    apply: function( resp ) { 
        /* valid || invalid */
        var that = this;
        for( var n in resp.validations ) {
            var v = resp.validations[n];
            var err = v.invalid || v.error;

            var w = this.form.find('.field-' + n );
            var msg = this.form.find('.field-' + n + '-message');
            if( err ) {
                w.addClass('invalid');
                msg.addClass('invalid').html( err ).fadeIn('slow');
            } else {
                w.addClass('valid');
                msg.addClass('valid').html( v.valid ).fadeIn('slow');
            }
        }
    },
    clear: function() {
        var that = this;
        this.action.findFields().each(function() {
            var el = $(this);
            var n = el.attr('name');
            that.form.find('.field-' + n ).removeClass('invalid valid');
            that.form.find('.field-' + n + '-message').removeClass('invalid valid').html("").hide();
        });
    }
};


(function() {

Action = function(arg1,arg2) {
    var f,opts;

    if( arg1 && ( arg1.attr || arg1.nodeType == 1 ) ) {
        f = $(arg1);
        opts = arg2 || { };
    }
    else if( typeof arg1 == "object" ) {
        opts = arg1;
    }

    if( f ) {
        this.formEl = $(f);
        this.formEl.attr('method','post'); // always to POST method
        if( ! this.formEl.get(0) )
            throw "Action form element not found";
    }

    this._bind   = [ ];
    this.plugins = [ ];
    this.actionPath = null;
    this.opts = $.extend( { 
                    debug: false
                } , opts );

    this.statusTexts = {
        "waiting": "Progressing...",
        "error": "",
        "success": "Success"
        // "done":    "Done"
    };

    this.logger = new Logger;

    if( this.formEl ) {
        this.name = this.formEl.find('*[name=action]').val();
        if( ! this.name )
            throw "Action name is undefined.";
    }

    /* init plugins */
    var self = this;
    var pgs = Action._global_plugins; // or from global plugin list
    $(pgs).each( function(i,e) { 
        self.plug( e.plugin, e.options );
    });
};

/* i18n dictionary */
Action.dict = { };
Action.dict.zh_TW = { 
    /*
    "Complete": "完成",
    "Progressing": "處理中"
    */
};

Action.locale = new MiniLocale( Action.dict , "zh_TW" );
Action.loc = Action.locale.getloc();
// Action.loc = new MiniLocale( Action.dict , "zh_TW" , { returnLoc: true } );

Action._global_plugins = [ ];

/*
Action.plug( plugin function , plugin options )

    register a plugin.
*/
Action.plug = function( plugin , opts ) {
    this._global_plugins.push( { plugin: plugin, options: opts });
};

Action.reset = function() {
    this._global_plugins = [];
};

Action.importdict = function( subdict ) {
    for(var lang in subdict) {
        Action.dict[lang] = $.extend( Action.dict[lang] , subdict[lang] );
    }
};

/* factory method , create an Action object from a form. */
Action.form = function(form,opts) {
    opts = opts || { };
    var a = new Action(form,opts);
    return a;
};

Action.prototype = {

    form: function(f) {
        if(f) {
            this.formEl = $(f);
            this.actionName = this.formEl.find('input[name=action]').val();
            this.opts.validation = false;
        }
        return this.formEl;
    },

    log: function() {
        if( window.console ) {
            console.log( console , arguments );
        }
        this.logger.log( arguments );
    },

    plug: function(plugin,options) {
        var p = new plugin(this, options );
        var p_dict = p.dict();
        Action.importdict( p_dict );
        this.plugins.push(p);
        return p;
    },

    /*
     options:

        validation: boolean || {style}
        status: [boolean] || {
                    waiting: [string]
                    done:    [string]
                }
        highlight: [boolean]
        submit:
        error:

    */
    options: function(opts)  { 

        var that = this;
        this.opts = $.extend( this.opts , opts );
        if( opts.validation ) {
            if( typeof opts.validation == "string" ) 
                this.setValidateStyle( opts.validation );
            this._initValidation();
        }

        if( opts.debug ) {
            this.msgWindow = MessageWindow.create();
            this.msgWindow.show();
            this.logger.hook( function(args) { 
                that.msgWindow.log( args ); 
            });
        }

        if( opts.highlight )
            this._initHighlight();

        if( opts.submit )
            this.form().submit( function() { 
                return opts.submit( that );
            });

        return this;
    },

    _initHighlight: function() { 
        this.highlight = new ActionHighlight( this );
    },

    /* appends validation div to input element. */
    _initValidation: function() {
        var that = this;

        // default validation style: perfield
        var style = this.getValidateStyle() || "perfield";
        if( style == "perfield" ) {
            this.perfield = new ActionPerfield(this);
        }
        return this;
    },


    validate: function() {
        var f = this.form();

    },
    

    getValidateStyle: function() { return this.vStyle; },
    setValidateStyle: function(s) { 
        if( s == "perfield" )
            this.vStyle = s;
    },

    enableInputs: function() {
        this.findFields()
            .removeAttr('disabled');
    },

    disableInputs: function() {
        this.findFields()
            .attr('disabled','disabled');
    },


    /* find text fields */
    findFields: function() {
        return this.form().find(
                  ' input[type=text], '
                + ' input[type=password], '
                + ' input[type=checkbox], '
                + ' input[type=radio], '
                + ' textarea, '
                + ' select');
    },

    // Extract messages and validation messages, then display them.
    //   1. add message to embedded message div
    //   2. remove field highlight
    //   3. setup field highlight
    //   4. clear validation messages
    //   5. setup validation messages
    displayValidation: function(resp) {
        var that = this;
        var s = this.getValidateStyle() || "perfield";


        if( s == "perfield" ) {
            // XXX: BROKEN 
            // for validation errors
            if ( resp.validations ) {
                this.perfield.clear();
                this.perfield.apply( resp );
            }
        }

        if( this.opts.highlight ) { 
            var args = [ ];
            for( var name in resp.validations ) {
                var attrs = resp.validations[name];
                if( attrs.error || attrs.invalid )
                    args.push({ name: name, type: "error" });
                else if ( attrs.success )
                    args.push({ name: name, type: "success" });
            }
            this.highlight.clear();
            this.highlight.apply( args );
        }
    },

    extErrorMsgs: function(resp) {
        var errs = [ ];
        for ( var field in resp.validations ) {
            var v = resp.validations[field];
            var err = v.invalid || v.error;
            if( err )
                errs.push( err );
        }
        return errs;
    },

    setPath: function(path) { this.actionPath = path; },

    growlError: function(resp,opts) {
        return this.growl( resp.responseText , $.extend( opts, { theme:'error' } ) );
    },

    /*
    Action.growl(text,option)
        Dispatch growler to growl.
    */
    growl: function(text,opt) {
        return $.jGrowl(text,opt);
    },

    /* load action arguments from result */
    loadResult: function(rs) {
        var args = rs.args;
        if( args ) {
            var f = this.form();
            if( ! f.get(0) )
                if( window.console ) { console.error( 'form not found.' ); }

            for ( var name in args ) {
                var input = f.find("*[name="+name+"]");
                if( input.attr('type') == "radio" || input.attr('type') == "checkbox" ) {
                    input.filter("[value=" + args[name] + "]").attr('checked',true);
                } else {
                    input.val( args[name] );
                }
            }
        } else {
            if(window.console)
                console.error( 'empty result args' );
        }
    },

    /*

    .run() or runAction()
        run specific action

    .run( 'Delete' , { table: 'products' , id: id } , function() { ... });

    
    .run( [action name] , [arguments] , [options] or [callback] );
    .run( [action name] , [arguments] , [options] , [callback] );


    Event callbacks:

            * onSubmit:    [callback]
                            callback before sending request

            * onSuccess:   [callback]
                            success callback.

    options:

            * validator:    [callback(formData)]
                            validator function, return false to stop submitting
                            action.

                            Validator function takes a formData hash to validate data

                            The validator function should return an Array [
                            {boolean} , {message} ] or {boolean}.
                            
            
            * notify:       1 XXX
            * growl:        1 XXX
                            show message box


            * confirm:      [text]    
                            should confirm 

            * removeRegion: [element] 
                            the element in the region. to remove region.

            * emptyRegion:  [element] 
                            the element in the region. to empty region.


            * removeTr:     [element] 
                            the element in the tr.

            * remove:       [element] 
                            the element to be removed.

            * clear:        [bool]
                            clear text fields
    */
    run: function(actionName,args,arg1,arg2) {

    // try 
    {
        debug( 'method run' , args , arg1, arg2 );

        var cb;
        var options = {  };
        if( typeof arg1 == "function" ) {
            cb = arg1;
        } else if ( typeof arg1 == "object" ) {
            options = arg1;
            if( typeof arg2 == "function" ) {
                cb = arg2;
            }
        }

        if( options.confirm ) {
            if( ! confirm( options.confirm ) ) {
                return false;
            }
        }

        var data = $.extend({ action: actionName }, args );

        /* TODO: we should get form field elements here ,
        * and append validation message to fields.
        * */
        if( options.validator ) {
            var ret = options.validator( data );
            if( typeof ret == "boolean" && ret === false ) {
                return false;
            } 
            else if ( typeof ret == "object" ) {
                if ( ! ret[0] ) {
                    $.notify( ret[1] , { theme: 'error' } );
                    return false;
                }
            }
        }

        this.log( "Running action: " , actionName , 'Args' , args , 'Options' , options );

        if( options.onSubmit )
            options.onSubmit();

        options.disableInput = true;

        var formEl = this.form();
        if( formEl && options.disableInput )  {
            this.disableInputs();
        }

        // can not use attr('action') this will get "action" input value.
        var sendto;

        /*
            Old code: This fix for jQuery 1.4.x version.
        if( formEl && this.form().get(0).getAttribute("action") ) {
            sendto = this.form().get(0).getAttribute("action");
            if( typeof sendto == "object" )
                sendto = sendto.value;
        }
        */
        // XXX: move actionPath to Global config.
        sendto = this.actionPath ? this.actionPath : window.location.pathname;

        /* let pages know, we only want a json result, not a html page. */
        data.__ajax_request = 1;


        var that = this;
        var errorHandler = function(resp) { 

                if( window.console )
                    console.error( resp.responseText ); 

                if( formEl && options.disableInput ) {
                    that.enableInputs();
                }
                that.growlError( resp.responseText );
            };

        var successHandler = function(resp) { 

                debug( 'Run success handler' , resp );

                if( formEl && options.disableInput ) {
                    that.enableInputs();
                }

                if( options.onSuccess ) {
                    options.onSuccess( resp ); }

                if( cb ) {
                    var ret = cb.call( that , resp );
                    if ( ret )
                        return ret;
                }

                if( resp.error ) {
                    $.jGrowl( resp.message , { theme: 'error' } );
                }

                if( resp.success ) {
                    if( options.clear ) {
                        formEl.find( 'input[type="text"] , input[type="file"], input[type="password"], textarea' ).each(function(i,e) {
                                // it's not action name field, clear it.
                                if( $(this).attr('name') != "action" )
                                    $(this).val("");
                        });
                    }

                    var reg;
                    var el =
                        options.refreshSelf ||
                        options.refresh ||
                        options.refreshParent ||
                        options.refreshWithID ||
                        options.removeRegion ||
                        options.emptyRegion;

                    if( el )
                        reg = Region.of( el );

                    if( reg ) {
                        if( options.refreshSelf || options.refresh ) {
                            reg.refresh();
                        }
                        else if( options.refreshParent ) {
                            reg.parent().refresh();
                        }
                        else if( options.refreshWithID ) {
                            reg.refreshWith( { id: resp.data.id } );
                        }
                        else if( options.removeRegion ) {
                            reg.fadeRemove();
                        }
                        else if( options.emptyRegion ) {
                            reg.fadeEmpty();
                        }
                    }

                    if( options.removeTr ) {
                        var el = $(options.removeTr);
                        el = $(el.parents('tr').get(0));
                        el.fadeOut( 'fast' , function() {
                            el.remove();
                        });
                    }
                    if( options.remove ) {
                        var el = $(options.remove);
                        el.fadeOut('fast', function(){  el.remove();  });
                    }




                    if( options.reload ) {

                        setTimeout( function() {
                            window.location.reload();
                        } , options.delay || 0 );
                    } 
                    else if( options.redirect ) {
                        that.growl( _('Redirecting...') );
                        setTimeout( function() {
                            window.location = options.redirect;
                                }, options.delay || 0 );
                    }
                    else if( resp.redirect ) {
                        that.growl( _('Redirecting...') );
                        setTimeout( function() {
                            window.location = resp.redirect;
                                }, options.delay || 0 );
                    }




                }
        };

        debug( 'Running ajax: ' + sendto , data );

        var that = this;
        $.ajax({
            url: sendto ,
            data: data,
            dataType: 'json',
            type: 'post',
            error: errorHandler,
            success: successHandler
        });
    } 

    /*
    catch( e ) 
    {
        if( window.console ) {
            console.error( e.name , e.message, e.description );
        } else {
            alert( e.name + ":" + e.message );
        }
    }
    */
    return false;

    },

    getData: function() {
        var data = {};
        var f = this.form();

        var that = this;

        function isIndexed(n) {
            return n.indexOf('[]') > 0;
        }

        // get data from text fields
		f.find(   
                  'select, '
                + 'input[type=text], '
                + 'input[type=hidden], '
                + 'input[type=checkbox], '
                + 'input[type=radio], '
                + 'input[type=password], '
                + 'textarea' ).each(function(i,n) {

            var el = $(n);
            var val = $(n).val();
            var name = el.attr('name');
            if( ! name ) 
                return;

            if( typeof val == "object" || typeof val == "array" )
                val = val.toString();

            // that.debugWindow.debug( val );

            // for checkbox(s), get their values.
            if( el.attr('type') == "checkbox" ) {
                if ( el.is(':checked') ) {
                    if( isIndexed( name ) ) {
                        if ( ! data[name] )
                            data[name] = [];
                        data[ name ].push( val );
                    } else {
                        data[ name ] = val;
                    }
                } 
            } 
            else if( el.attr('type') == "radio" ) { 
                if( el.is(':checked') ) {
                    if( isIndexed( name ) ) {
                        if ( ! data[name] )
                            data[name] = [];
                        data[ name ].push( val );
                    } else data[ name ] = val;
                }
                else {
                    if( ! data[name] )
                        data[ name ] = null;
                }
            }
            else {
                // if it's name is an array
                if( isIndexed( name ) ) {
                    if ( ! data[name] )
                        data[name] = [];
                    data[ name ].push( val );
                } else {
                    data[ name ] = val;
                }
            }
		});

        if( this.opts.debug ) {
            this.debugWindow.debug( data );
        }

        return data;
    },


    /*
   
    onSubmit( options , callback );

    Usage:

        $(document.body).ready(function() {
            Action.form( $('#create_user') )
                .options({ validation: true })
                .onSubmit({ refreshWithID: $('#create_user') });
        });

    */
    onSubmit: function(arg1,arg2) {
        var that = this;
        this.form().submit(function() {
            try {
                // run Action.submit method( )
                return that.submit(arg1,arg2); 
            } catch(e) { 
                if( window.console ) {
                    console.error(e);
                } else {
                    alert(e.message);
                }
            }
            return false; // do not use default form submit.
        });
    },

    submit: function(arg1,arg2) 
    {
        debug( 'submit method' ,arg1, arg2 );

        var that = this;
        var cb,fEl,data;
        var options = {};

        /* detect arguments */
        if( typeof arg1 == "object" ) {
            options = arg1;
            if ( arg2 && typeof arg2 == "function" )
                cb = arg2;
        }
        else if( typeof arg1 == "function" ) {
            cb = arg1;
        }

        /* get form element */
        fEl = $(this.form());

        data = this.getData();
        data['__ajax_request'] = 1;  // it's an ajax request

        if( options.beforeSubmit )
            options.beforeSubmit.call( this, data );

        $(that).trigger('action.before_submit',[data]);

		// submit handler {{{
        var submitHandler = function(resp) {
            $(that).trigger('action.on_result',[resp]);

            if( window.console )
                console.info( "Action response: " , resp );

            if( cb ) {
                var ret = cb(resp);
                if( ret )
                    return ret;
            }

            // it's a little different with runAction options, but mostly the same.
            // XXX: do some refactoring with this.
            that.displayValidation( resp );

            if( resp.success ) { 

                var reg = Region.of( fEl );
                if( reg ) {
                    if( options.refreshSelf || options.refresh )
                        reg.refresh();

                    if( options.refreshParent )
                        reg.parent().refresh();

                    if( options.refreshWithID )
                        if( resp.data && resp.data.id )
                            reg.refreshWith( { id: resp.data.id } );

                    if( options.removeRegion )
                        reg.fadeRemove();

                    if( options.emptyRegion )
                        reg.fadeEmpty();

                }

                if( options.remove ) {
                    var el = $(options.remove);
                    el.fadeOut('fast', function(){  el.remove(); });
                }

                if( options.clear ) {
                    that.form().find( 'input[type="text"] , input[type="file"], input[type="password"], textarea' ).each(function(i,e) {
                            // it's not action name field, clear it.
                            if( $(this).attr('name') != "action" )
                                $(this).val("");
                    });
                }

                // reload page
                if( options.reload ) {
                    setTimeout( function() {
                        window.location.reload();
                    } , options.delay || 0 );
                } 
                else if( options.redirect ) {
                    that.growl( _('Redirecting...') );
                    setTimeout( function() {
                        window.location = options.redirect;
                            }, options.delay || 0 );
                }
                else if( resp.redirect ) {
                    that.growl( _('Redirecting...') );
                    setTimeout( function() {
                        window.location = resp.redirect;
                            }, options.delay || 0 );
                }


            }
            return true; /* this will override the handler in run method */
        };
		// }}}

        // overwrite default submitHandler
        if( options.onSubmit )
            submitHandler = options.onSubmit;

        /* If file field detected, then we should use AIM instead of normal ajax request. */
        var hasfile = fEl.find("input[type=file]").get(0);
        if( hasfile ) {

            if( options.beforeUpload )
                options.beforeUpload.call( this, fEl, data );

            // auto setup enctype for uploading file.
            fEl.attr( 'enctype' , "multipart/form-data" );
            fEl.find('.action-progress').fadeOut(function() { 
                $(this).remove();
            });

            // pass __ajax_request param to generate a html page or region.
            if( ! options.replaceForm )
                fEl.append( $('<input>').attr({ type:"hidden", name:"__ajax_request", value: 1 }) );


            // AIM bridge
            return AIM.submit( fEl.get(0) , {
                onStart: function() { 
                    // fEl.html( "Uploading..." );
                    that.log( "AIM onStart" );
                    $.scrollTo( fEl , 300 );
                    return true 
                },
                onComplete: function(respText) { 

                    that.log( "AIM onComplete" , respText );

                    if( options.replaceForm ) {
                        fEl.html( respText );
                        return true;
                    }

                    var json = JSON.parse( respText );

                    if( options.onUpload )
                        options.onUpload.call( that, json );
                    else
                        submitHandler.call( that, json );

                    if( options.afterUpload )
                        options.afterUpload.call( that , fEl , json );

                    return true;
                }
            });
        }

        /* call run method, and pass our submit handler */
        return this.run( data.action, data, options, submitHandler );
    },

    /* 
    (Action object).submitWith( args, ... )
     */
    submitWith: function(extendData,arg1,arg2) { 
        var options = { };
        var cb;

        // arg2 is option
        if( typeof arg1 == "object" ) {
            options = arg1;
            if( typeof arg2 == "function" )
                cb = arg2;
        }
        else if ( typeof arg1 == "function" ) {
            cb = arg1;
        }
        var data = $.extend( this.getData(), extendData );
        return this.run( data.action , data , options , cb );
    }


};

})();


/* action helper functions */
function submitActionWith( f , extendData , arg1 , arg2 ) {
    return Action.form(f).submitWith( extendData, arg1, arg2);
}

function submitAction(f,arg1,arg2) {
    return Action.form(f).submit(arg1,arg2);
}

function runAction(actionName,args,arg1,arg2) {
    return (new Action).run( actionName, args,arg1,arg2);
}

// Export Action to jQuery.
$.Action = Action;



/* Action plugin base class 
 **/
var ActionPlugin = Class.extend({
    init: function(action,config) { 
        if( ! action )
            throw "Action object is required.";
        this.action = action;
        this.config = config;

        // init events
        $(this.action).bind('action.on_result', this.onResult );

        /* when action init */
        $(this.action).bind('action.on_init',this.onInit);

        /* when the action result presents success */
        $(this.action).bind('action.on_success',this.onSuccess);
        /* when the action result presents error */
        $(this.action).bind('action.on_error',this.onError);

        /* before user submit the form */
        $(this.action).bind('action.before_submit',this.beforeSubmit);

        /* after user submit the form */
        $(this.action).bind('action.after_submit',this.afterSubmit);

        this.load();
    },

    /* Accessors */
    action: function() { 
        return this.action; 
    },

    form:   function() { 
        return this.action.form(); 
    },

    dict:   function() { 
        return {  };
    },
    config: function(config) { 
        if( config )
            this.opts = $.extend( this.opts , config );
        return this.opts;
    },

    load: function()  {  
          
    },

    /* Filters
    */

    /* filter the data before submit or run, this allow plugin to change/append
    * some data. */
    filterData: function(data) { return data; },


    /*
    * Event handlers 
    * */
    onInit:       function(ev) {  },
    onResult:     function(ev, r ) {  },
    onSuccess:    function(ev, r ) {  },
    onError:      function(ev, r ) {  },
    beforeSubmit: function(ev, d ) { return d; },
    afterSubmit:  function(ev, r ) {  },
    onSubmit:     function(ev, d ) {  }
});

var ActionGrowler = ActionPlugin.extend({
    init: function(action,config) {
        this._super(action,config);
    },
    growl: function(text,opts) {
        return $.jGrowl(text,opt);
    },
    onResult: function(resp) {
        if( ! resp.message ) {
            if( resp.error && resp.validations ) {
                var errs = this.extErrorMsgs(resp);
                for ( var i in errs )
                    this.growl( errs[i] , { theme: 'error' } );
            }
            return;
        }

        if( resp.success ) {
            this.growl( resp.message , this.opts.success );
        } else {
            this.growl(resp.message, $.extend( this.opts.error , { theme: 'error' } ));
        }

        if( window.console )
            if( resp.error )
                console.error( resp.message );
            else if ( resp.success )
                console.info( resp.message );
    }
});


/* 
Action Message Box Plugin 
    
Handle action result, and render the result as html.

TODO:

Move progressbar out as a plugin.
*/
var ActionMsgbox = ActionPlugin.extend({
    dict: function() {
        return {
            "zh_TW": {
                "Complete": "完成",
                "Progressing": "處理中"
            }
        };
    },
    load: function() {
        /* since we use Phifty::Action::...  ... */
        var actionName = this.action().name;
        var actionId = actionName.replace( /::/g , '-' );

        this.cls    = 'action-' + actionId + '-result';
        this.ccls   = 'action-result';  // common class
        this.div    = this.form().find( '.' + this.cls );
        if( ! this.div.get(0) ) { 
            this.div = $('<div/>').addClass( this.cls ).addClass( this.ccls ).hide();
            this.form().prepend( this.div );
        }

        this.div.empty().hide();
    },

    beforeSubmit: function(d) { 
        this.wait();
    },

    onResult: function(resp) { 
        var that = this;
        if( resp.success ) {
            var sd = $('<div/>').addClass('success').html(resp.message);
            this.div.html( sd ).fadeIn('slow');
        }
        else if ( resp.error ) {
            this.div.empty();
            var ed = $('<div/>').addClass('errors');
            if( resp.message ) {
                var et = $('<div/>').addClass('error-title').html(resp.message);
                ed.append( et );
            }
            this.div.append( ed ).fadeIn('slow');
        }

        if( resp.validations ) {
            var errs = this.extErrorMsgs(resp);
            $(errs).each(function(i,e) { 
                that.addError(e);
            });
        }
    },


    /* private methods */
    extErrorMsgs: function(resp) {
        var errs = [ ];
        for ( var field in resp.validations ) {
            var v = resp.validations[field];
            var err = v.invalid || v.error;
            if( err )
                errs.push( err );
        }
        return errs;
    },

    addError: function(msg) { 
        var d = $('<div/>').addClass('error-message').html(msg);
        this.div.find('.errors').append(d);
    },
    wait: function() { 
        var ws = $('<div/>').addClass('waiting').html( Action.loc("Progressing") );
        this.div.html( ws ).show();
    }
});
