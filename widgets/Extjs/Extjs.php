<?php
namespace Phifty\Widgets;
use Phifty\Widget;

class Extjs extends Widget 
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
