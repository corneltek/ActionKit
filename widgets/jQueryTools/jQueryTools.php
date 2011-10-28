<?php

namespace Phifty\Widgets;
use Phifty\Widget;

class jQueryTools extends Widget
{
    public $images;
    public $config = array();
	function js()
	{
        return array(
            'jquery.tools.min.js',
            'scrollable/scrollable.js',
            'scrollable/scrollable.navigator.js',
        );
	}
}
