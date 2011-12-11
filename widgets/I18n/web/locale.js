
/*
   Locale.js

   borrowed from jifty i18n plugin.

   Added gettext format support.
    
        _('Hello %1' , 'John')


*/
Locale = {
    varreg: /^%(\d+)$/,
    init: function(params) {
        this.lang = params.lang || 'en';
        if (params["dict_path"]) {
            this.dict_path = params["dict_path"];
            this.dict = this.load(this.lang);
        }
    },

    'switch': function(lang) {
        this.dict = this.load(lang);
    },

    load: function(lang) {
        var d = {};
        jQuery.ajax({
            url: this.dict_path + "/" + lang + ".json",
            type: 'get',
            async: false,
            dataType: 'json',
            error: function(e) { },
            success: function(dict) {
                // eval("d = " + ( dict || "{}" ) );
                d = dict || {};
            }
        });

        if(d && window.console )  {
            for ( var k in d ) {
                console.log( d[k] );
            }
        }
        return d;
    },

    /*
       Locale.applyArgs( 'hello %1' , [ "John" ] );
    */
    applyArgs: function(str,args) {
        // support gettext style.
        var tokens = str.split( /(%\d+)/ );
        for ( var i = 0; i < tokens.length; i++ ) {
            var match = Locale.varreg.exec(tokens[i]);
            if(match)
                tokens[i] = args[parseInt( match[1] ) ];
        }
        return tokens.join("");
    },

    loc: function(str) {
        var dict = this.dict;
        var msgstr = dict[str];
        if( msgstr )
            return this.applyArgs(msgstr,arguments);
        return this.applyArgs( str , arguments );
    },

    prettyLocalTime: function(time) {
        var system_date = new Date(time);
        var user_date = new Date();
        delta_minutes = Math.floor((user_date - system_date) / (60 * 1000));
        if (Math.abs(delta_minutes) <= (7*24*60)) {
            var distance = this.prettyTime(delta_minutes);
            if (delta_minutes < 0) {
                return distance + _(' from now');
            } else {
                return distance + _(' ago');
            }
        } else {
            return system_date.toLocaleDateString();
        }
    },

    prettyTime: function(minutes) {
        if (minutes.isNaN) return "";
        minutes = Math.abs(minutes);
        if (minutes < 1) return _('less than a minute');
        if (minutes < 50) return _(minutes + ' minute' + (minutes == 1 ? '' : 's'));
        if (minutes < 90) return _('about one hour');
        if (minutes < 1080) return (Math.round(minutes / 60) + ' hours');
        if (minutes < 1440) return _('one day');
        if (minutes < 2880) return _('about one day');
        else return (Math.round(minutes / 1440) + _(' days'))
    }
};

Locale.dict = {};

window._ = function() {
    return Locale.loc.apply(Locale, arguments);
};

Locale.init({ lang: 'zh_TW' , dict_path: '/static/dict/' });
