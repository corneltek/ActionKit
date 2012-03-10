<?php
namespace Phifty\Assets;

class lightbox extends \Phifty\Asset\Asset
{
    function js()
    {
        return array( 'js/jquery.lightbox-0.5.min.js' );
    }

    function css()
    {
        return array( 'css/jquery.lightbox-0.5.css' );
    }
}

