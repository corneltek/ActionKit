<?php
namespace Phifty\Widgets;

class Facebox extends \Phifty\Widget 
{
    function js()
    {
        return array('facebox/src/facebox.js');
    }

    function css()
    {
        return array('facebox/src/facebox.css');
    }

}

