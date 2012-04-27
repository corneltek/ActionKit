<?php
namespace Phifty\Assets;

use Phifty\Asset\Asset;

class Facebox extends Asset 
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

