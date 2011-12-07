<?php
namespace Core;

class Application extends \Phifty\MicroApp
{
    function init()
    {
        # $this->route( '/' , 'Index' );
        $this->route( '/not_found' , 'NotFound' );
    }

    function css()
    {
        return array(
            'css/blueprint/compressed/screen.css',
            'css/common.css',
            'css/phifty.css',
            'css/action.css',
            # 'jquery-ui/css/redmond/jquery-ui-1.8.14.custom.css',
            'jquery-ui-smoothness/css/smoothness/jquery-ui-1.8.16.custom.css',
        );
    }

    function js()
    {
        return array(
            'js/jquery/jquery-1.6.2.min.js',
            'js/json2.js',
            'js/webtoolkit.aim.js',
            'js/minilocale.js',
            'js/locale.js',
            'js/js-model-0.9.4.min.js',
            'js/jquery.scrollTo-min.js',
            'js/jquery.oembed.js',
            'js/simpleclass.js',
            'js/region.js',
            'js/action.js',
            'js/crud.js',
            'js/tinymce/tiny_mce.js',
            'js/tinymce_helper.js',
            'jquery-ui-smoothness/js/jquery-ui-1.8.16.custom.min.js',
        );
    }

}

