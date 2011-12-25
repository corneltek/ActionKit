<?php

namespace Phifty\Assets;
use Phifty\Asset\Asset;

class jQueryMobile extends Asset
{
    function css()
    {
        return array(
            'jquery.mobile-1.0.min.css',
            'jquery.mobile.structure-1.0.min.css',
        );
    }

    function js()
    {
        return array(
            'jquery.mobile-1.0.min.js',
        );
    }
}
