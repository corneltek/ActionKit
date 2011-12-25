<?php

namespace Phifty\Assets;
use Phifty\Asset\Asset;

class Galleria extends Asset
{
    function js() 
    {
        return array( "galleria-1.2.5.js" );
    }

    function css() 
    {
        return array( "themes/twelve/galleria.twelve.css" );
    }
}
