<?php

namespace Phifty\Assets;
use Phifty\Asset;

class jQueryTools extends Asset
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
