<?php
namespace Phifty\Widgets;
use Phifty\Widget;

class Ext4js extends Widget 
{
    function js()
    {
        return array( 'js/ext-all-scoped.js', 'js/fix.js' );
    }

    function css() 
    {
        return array( 'css/ext-all-scoped.css' );
		// widgets/Ext4js/web/css/ext-all-scoped.css
    }

}
