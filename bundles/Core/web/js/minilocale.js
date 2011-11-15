/* 
MiniLocale.js

Author: Cornelius <cornelius.howl@gmail.com>

* Support global dictionary.
* Support dictionary merging
* Support dictinoary from json. (ajax)

*/
var MiniLocale = function(dict,lang,opts) {
    this.opts = $.extend( {
        returnLoc: false,
        ignoreCase: false,
        ignoreLangCase: false,
        injectLoc: false
    } , opts );

    if( this.opts.ignoreLangCase )
        lang = lang.toLowerCase();

    this.lang = lang;
    this.dict = dict || { };

    if( this.opts.ignoreCase ) {
        // convert dictionary keys to lowercase.
        for( var msgid in this.dict )
            this.dict[ msgid.toLowerCase() ] = this.dict[ msgid ] ;
    }

    var that = this;


    /* applyArgs( 'hello %1' , [ "John" ] ); */
    var varreg = /^%(\d+)$/;
    var applyArgs = function(str,args) {
        // support gettext style.
        var tokens = str.split(/(%\d+)/);
        for(var i = 0; i < tokens.length; i++) {
            var match = varreg.exec(tokens[i]);
            if(match)
                tokens[i] = args[parseInt( match[1] ) ];
        }
        return tokens.join("");
    };

    var loc = this._loc = function(msgid) {
        var msg = msgid;
        if( that.opts.ignoreCase )
            msgid = msgid.toLowerCase();

        if( that.dict[that.lang] && that.dict[ that.lang ][ msgid ] )
            msg = that.dict[ that.lang ][ msgid ];
        return applyArgs( msg , arguments );
    };

    var caller = arguments.callee.caller;
    if( typeof caller == "function" && this.opts.injectLoc )
        caller.prototype.loc = loc; // injects loc method to caller's prototype.

    if( this.opts.returnLoc )
        return loc;
};


MiniLocale.prototype = {

    "import": function(dict) {
        this.dict = $.extend( this.dict , dict );
    },

    getloc: function() { return this._loc; },

    loc: function() {
        return this._loc.apply( this, arguments );
    },

    'switch': function(lang) {
        this.lang = lang;
        if( ! this.dict[ lang ] ) {
            if( window.console )
                console.info( lang + " dictionary is empty." );
        }
    }
};



