<?php
namespace Phifty\Assets;

class chosen extends \Phifty\Asset\Asset
{
    function css()
    {
        return array('chosen.css');
    }

    function js()
    {
        return array('chosen.jquery.min.js');
    }
}



