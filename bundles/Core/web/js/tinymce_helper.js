

// tiny mce helpers
/*

   configuration: 
   http://www.tinymce.com/wiki.php/Configuration:mode

*/
tinyMCE_Helper = { 

    _schema: { 

        "adv1": {
                // General options
                theme : "advanced",
                plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagechooser",

                mode : "specific_textareas",
                editor_selector : "mceEditor",

                // Theme options
                theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,table,removeformat,code,|,image,link,|,preview,imagechooser",
                theme_advanced_buttons2 : "",
                theme_advanced_buttons3 : "",
                theme_advanced_buttons4 : "",

                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,

                // default language
                language : "zh-tw",
                height : "480px",
                formats : {
                        alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
                        aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
                        alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
                        alignfull : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'full'},
                        bold : {inline : 'span', 'classes' : 'bold'},
                        italic : {inline : 'span', 'classes' : 'italic'},
                        underline : {inline : 'span', 'classes' : 'underline', exact : true},
                        strikethrough : {inline : 'del'},
                        customformat : {inline : 'span', styles : {color : '#00ff00', fontSize : '20px'}, attributes : {title : 'My custom format'}}
                },

                // Example content CSS (should be your site CSS)
                content_css : "/ph/Core/js/tinymce_content.css",

                // Skin options
                skin : "o2k7",
                skin_variant : "silver",
                convert_urls : false

                // document_base_url : "/ph/Core/js/tinymce/"
        }
    } 
};

tinyMCE_Helper.add_schema = function(name,schema) {
    this._schema[ name ] = schema;
};

tinyMCE_Helper.get_schema = function(name,args) {
    var s =  this._schema[ name ];
    if( typeof s == "function" ) {
        return s( args );
    }
    return s;
};

tinyMCE_Helper.init = function( name , args ) {
    name = name || "default";
    var schema = this.get_schema( name , args );
    tinyMCE.baseURL = '/ph/Core/js/tinymce';
    tinyMCE.init( schema );
};

function use_tinymce(name,args) {
    $(document.body).ready(function() {
        $(tinyMCE.editors).each(function(){
            tinyMCE.remove(this);
        });
        tinyMCE_Helper.init( name , args );
    });
}

