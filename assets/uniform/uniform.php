<?php
namespace Phifty\Assets;

class uniform extends \Phifty\Asset\Asset
{
    function css()
    {
        return array( 'css/uniform.default.css',);
    }

    function js()
    {
        return array( 'jquery.uniform.min.js' );
    }
}

