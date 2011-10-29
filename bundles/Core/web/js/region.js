/*
    jQuery Region
    Copyright (C) 2010 Yo-An Lin <cornelius.howl@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/*
    $('#panel').asRegion().load( 'path/to/content' , { ... } );
    $('#panel').asRegion({ history: true }).load( ... );

    Region.load( $('#panel') , 'path/to/content' , { id: 123, name: "foo" } );
    Region.append( $('#panel') , 'path/to/content' , { id: 123, name: "foo" } );
    Region.before( $('#panel') , 'path/to/content' , { id: 123, name: "foo" } );
    Region.after( $('#panel') , 'path/to/content' , { id: 123, name: "foo" } );

    var regs = Region.get( [ $('#region1') , $('#region2') , $('#region3') ] );
    var regs = Region.get( [ '#region1' , '#region2' , '#region3' ] );
    var reg = Region.get( '#region1' );

    var r = Region.of( $('#subpanel') );   // get $('#panel')
    r.refresh();

    var rs = r.subregions(); // get subregions

    rs.remove();
    rs.fadeRemove();

    rs.empty();
    
    r.refreshSubRegions();
*/

var Region;
var RegionNode;
var RegionHistory;

Region = { 
    /* global options */
    opts:  { 
        method: 'post',
        gateway: null,
        statusbar: true,
        effect: "slide"  // current only for closing.
    }
};


// XXX: this should be auto generated.
Region.dict = {  };
Region.dict.zh_TW = { 
    "Loading content ...": "載入內容中 ...",
    "Loading": "載入中"
};

Region.loc = new MiniLocale( Region.dict , "zh_TW" , { returnLoc: true } );

Region.config = function(opts) { 
    this.opts = $.extend( this.opts , opts );
};


RegionHistory = function() {
    this._history = [ ];
};

RegionHistory.prototype = {
    push: function(path,args,cb) { 
        this._history.push({ 
            path: path,
            args: args,
            callback: cb
        });
    },
    pop: function() { 
        return this._history.pop();
    }
};

jQuery.fn.asRegion = function( opts ) { 
    var r;
    r = $(this).data('region');
    if( r )
        return r;

    var r = new RegionNode( this, null, opts );
    $(this).data('region', r);
    return r;
};

/*

RegionNode class:

    * constructor(path,args,opts);
    * constructor(el);
    * constructor(el,path);
    * constructor(el,path,args);
    * constructor(el,path,args,opts);

RegionNode usage:
    
    This will create a new HTMLElement node for region:

        var r = new RegionNode(path,args , {   } );

        $( ).append( r.getEl() );

    Find an HTMLElement object and create a region node depends on it:

        var r = new RegionNode($('#region1'));
        r.refresh();
        r.refreshWith({ id: 123 });

        var r2 = new RegionNode($('#region2').get(0));
        r2.refresh();

*/

RegionNode = function(arg1,arg2,arg3,arg4) {
    var defaultOpts = { 
        debug: true,
        historyBtn: false,
        history: false
    };
    var el,meta,path,args,opts;

    function isRegionNode(e) {
        return typeof e == "object" && e instanceof RegionNode;
    }
    function isElement(e) {
        return typeof e == "object" && e.nodeType == 1;
    }
    function isjQuery(e) {
        return typeof e == "object" 
                && e.get
                && e.attr;
    }

    if( typeof arg1 == "object" ) {
        // if specifeid, force it
        if( arg2 )
            path = arg2;
        if( arg3 )
            args = arg3;
        if( arg4 )
            opts = arg4;
        opts = $.extend( defaultOpts , opts );

        if( isjQuery(arg1) ) {     // has jQuery.get method
            el = this.initFromjQuery( arg1 );
        } else if( isElement( pathOrEl ) ) {
            el = this.initFromElement( arg1 );
        } else if( isRegionNode( arg1 ) ) {
            return arg1;
        }

        meta = this.deparseMeta( el );

        // save attributes
        this.path = meta.path ? meta.path : path;
        this.args = meta.args ? meta.args : args;
        this.el   = el;
        this.opts = opts;

        

        // sync attributes
        this.save();

    } else if ( typeof arg1 == "string" ) {  // region path,
        path = arg1;
        args = arg2 || { };

        if( arg3 )
            opts = arg3;
        opts = $.extend( defaultOpts , opts );

        // construct new node
        var n = this.createRegionDiv();

        this.path = path;
        this.args = args;
        this.el   = n;
        this.opts = opts;

        // sync attributes
        this.save();

    } else {
        alert("Unknown Argument Type");
        return;
    }
};


RegionNode.prototype = {

    /* constructor */
    initFromjQuery: function(el) {
        if( el.get(0) == null ) {
            alert( 'Region Element not found.' );
            return;
        }
        return this.init(el);
    },

    initFromElement: function(e) {
        var el = $(e);
        return this.init(el);
    },

    init: function(el) {
        el.addClass( '__region' )
            .data('region',this);
        return el;
    },


    createRegionDiv: function() {
        var el = $('<div/>');
        return this.initFromElement( el );
    },

    /* write path, args into DOM element attributes */
    save: function() { 
        this.writeMeta( this.path , this.args );
        return this;
    },

    writeMeta: function(path,args) {
        this.el.attr({
            region_path: path,
            region_args: JSON.stringify(args)
        });
    },

    /* deparse meta from an element or from self._el */
    deparseMeta: function(el) {
        if( ! el )
            el = this.el;
        var path = el.attr('region_path');
        var args = el.attr('region_args');
        args = args ? JSON.parse( args ) : { };
        return { 
            path: path,
            args: args
        };
    },

    history: function(flag) {
        return this._history;
    },

    hasHistory: function() {
        return ( this.history()._history.length > 0 ) ;
    },

    saveHistory: function(path,args) { 
        if( ( this.opts.history || Region.opts.history )
                && this.path ) 
        {
            this.history().push( this.path , this.args );
            this.debug( "Save history: " + path );
        }
    },

    back: function(callback) {
        var a = this.history().pop();
        if( a )
            this._request( a.path , a.args );
    },

    initHistoryObject: function() {
        if( this.opts.history ) {
            this._history = new RegionHistory();
        }
    },

    createStatusbar: function() {
        return $('<div/>')
            .addClass('region-statusbar')
            .attr('id','region-statusbar');
    },

    getStatusbarEl: function() { 
        if( RegionNode.statusbar )
            return RegionNode.statusbar;

        var bar = $('#region-statusbar');
        if( bar.get(0) )
            return bar;

        bar = this.createStatusbar();
        $(document.body).append( bar );
        RegionNode.statusbar = bar;
        return bar;
    },


    /* waiting content */
    setWaitingContent: function(){
        if( Region.opts.statusbar ) {
            var bar = this.getStatusbarEl();
            bar.addClass('loading')
                .html( Region.loc("Loading content ...") )
                .show();
        }
        
        var waitingImg = $('<div/>').addClass('region-loading');
        this.el.html( waitingImg );
    },

    debug: function( str ) {
        if( window.console )
            console.log( str );
        if( this.conpre )
            this.conpre.prepend( str + "\n" );
    },

    _request: function(path, args, callback ) { 
        var that = this;
        this.setWaitingContent();

        if( this.opts.debug ) {
            var arg_strs = [ ];
            for( var k in args )
                arg_strs.push( k + ":" + args[k] );

            this.debug( "Requesting: " +  path + " <= { " + arg_strs.join("\n\t") + " }" );
            if( window.console )
                console.log( "Request region, Path:" , path , "Args:" , args );
        }

        function onError(e) {
            if( Region.opts.statusbar ) {
                var d = $('<div/>').addClass('region-message region-error');
                d.html( "Path: " + path + " " + ( e.statusText || e.responseText ) );
                that.getStatusbarEl().show().html( d );
            }

            that.el.html( e.statusText );

            if( window.console ) 
                console.error( path , args ,  e.statusText || e.responseText );
            else
                alert( e.message );
        }

        function onSuccess(html) {
            if( Region.opts.statusbar )
                that.getStatusbarEl().hide();

            that.el.fadeOut('fast',function() {
                $(this).html(html);
                $(this).fadeIn('fast');

                if( that.opts.historyBtn || Region.opts.historyBtn ) {
                    if( that.hasHistory() ) {
                        var backbtn = $('<div/>')
                            .addClass('region-backbtn')
                            .click(function() { 
                                that.back();
                            });
                        that.el.append( backbtn );
                    }
                }
            });

            if( callback )
                callback(html);
        }

        if( Region.opts.gateway ) {
            $.ajax({
                url: Region.opts.gateway ,
                type: Region.opts.method ,
                dataType: 'html',
                data: { path: path , args: args },
                error: onError,
                cache: false,
                success: onSuccess });
        } 
        else {
            $.ajax({
                url: path,
                data: args,
                dataType: 'html',
                type: Region.opts.method,
                cache: false,
                error: onError,
                success: onSuccess });
        }

    },
    getEl: function() { return this.el; },

    refresh: function( callback ) {
        this._request( this.path , this.args , callback );
    },

    refreshWith: function(args, callback) {
        var newArgs = $.extend( {} , this.args,args);
        this.args = newArgs;
        this.saveHistory();
        this._request( this.path , newArgs , callback );
        this.save();
    },

    load: function(path,args,callback) {
        if( path == null ) {
            path = this.path;
            args = args ? args : this.args;
        }
        this.replace(path,args, callback );
    },

    replace: function(path,args,callback) {
        this.saveHistory();
        this.path = path;
        this.args = args;
        this.save();
        this.refresh( callback );
    },

    // XXX: seems no use.
    of: function() {
        var el = this.el;
    },

    parent: function() {
        return new RegionNode($( this.el.parents('.__region').get(0) ));
    },

    subregions: function() {
        /* find subregion elements and convert them into RegionNode */
        return this.regionElements().map( function(e) { 
            return new RegionNode(e);
        });
    },

    regionElements: function() {
        return this.el.find('.__region');
    },

    // setup region content == empty
    empty: function() { this.el.empty(); },

    // setup region content (html)
    html: function(html) { 
        return this.el.html( html );
    },

    // remove region
    remove: function() { 
        this.el.remove();
    },

    // type: 1 for hide, 0 for show
    getEffectFunc: function(type) {
        var ef = Region.opts.effect;
        if( ef == "fade" )
            m = type ? jQuery.fn.fadeOut : jQuery.fn.fadeIn;
        else if ( ef == "slide" )
            m = type ? jQuery.fn.slideUp : jQuery.fn.slideDown;
        else
            m = type ? jQuery.fn.slideUp : jQuery.fn.slideDown; // default
        return m;
    },

    effectRemove: function() {
        var that = this;
        var m = this.getEffectFunc(1);
        m.call( this.getEl() , 'slow' , function() {
            that.getEl().remove();
        });
    },

    fadeRemove: function() {
        var that = this;
        this.effectRemove();
    },

    fadeEmpty: function() { 
        var that = this;
        var m = this.getEffectFunc(1);
        m.call( this.getEl() , 'slow' , function() {
            that.getEl().empty().show();
        });
    },

    removeSubregions: function() { 
        this.regionElements().map( function(e) {
            $(e).remove();
        });
    },

    refreshSubregions: function() {
        this.regionElements().map( function(e) {
            var r = new RegionNode(e);
            r.refresh();
        });
    },

    submit: function(formEl) {
        // TODO:
        // Submit Action to current region.
        //    get current region path or from form 'action' attribute.
        //    get field values 
        //    send ajax post to the region path

    },

    /* find sub regions by id or ... */
    find: function() {
              // XXX:
        this.el.find( );
    }
};



/* 

RegionNode = Region.get( HTML element, jQuery, RegionNode )
RegionNode Array = Region.get( Array )

    Get region(s):
    Find regions or fidn a parent region of a html element.

    Type:
        HTMLElement: return the region of this element.
        RegionNode: just return;
        typeof "string": return new RegionNode( jQuery(val) )
        jQueryObject: return new RegionNode( val );

*/
Region.get = function(el) {
    if( typeof el == "array" ) {
        return $(el).map( function(e,i) {
            return Region.getOne(e);
        });
    } else {
        return Region.getOne(el);
    }
};

Region.getOne = function(el) {
    if( el instanceof RegionNode )
        return el;
    else if( el.nodeType == 1 || el instanceof Element )
        return Region.of(el);
    else if( typeof el == "string" )
        return new RegionNode( $(el) );
    return new RegionNode(el);
};

Region.of = function(el) {
    var regEl = $(el).parents('.__region').get(0);
    if( ! regEl )
		return;
    var reg = $(regEl);
    return new RegionNode( reg );
};


Region.append = function(el,path,args) {
    var rn = new RegionNode(path,args);
    $(el).append( rn.getEl() );
    return rn.getEl() ? true : false;
};

Region.after = function(el,path,args) {
    var rn = new RegionNode(path,args);
    rn.refresh();

    $(el).after( rn.getEl() );
    return rn.getEl() ? true : false;
};


/* XXX: different behavior */
Region.before = function(el,path,args) {
    var rn = new RegionNode(path,args);
    rn.refresh();

    var p = $(el).parents('.__region');
    rn.getEl().hide();
    rn.getEl().insertBefore( p );
    rn.getEl().slideDown( );
    return rn.getEl() ? true : false;
};

/*

   Region.load( $('content'), 'path', { id: 123 } , function() {  } );
   Region.load( $('content'), 'path' , function() {  } );
   Region.load( $('content'), 'path' );

   Region.load doesn't sync path ,args to attirbute.
*/
Region.load = function(el,path,arg1,arg2) {
    var callback;
    var args = {  };
    if( typeof arg1 == "object" ) {
        args = arg1;
        if( typeof arg2 == "function" ) {
            callback = arg2;
        }
    } else if( typeof arg1 == "function" ) {
        callback = arg1;
    }

    var rn = new RegionNode(el);
    rn.replace( path, args );
    return rn;
};

Region.replace = function(el,path,arg1,arg2) {
    var rn = this.load(el,path,arg1,arg2);
    rn.save();
    return rn;
};

$.region = Region;
