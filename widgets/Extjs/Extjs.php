<?php
namespace Phifty\Widgets;
use Phifty\Widget;

class Extjs extends Widget 
{
    function js()
    {
        return array( 'js/fix.js', 'js/ext-all.js' );
        // return array( 'js/fix.js', 'js/ext-all-debug.js' );
    }

    function css() 
    {
        return array( 'resources/css/ext-all-scoped.css' );
		// widgets/Ext4js/web/css/ext-all-scoped.css
    }

}
