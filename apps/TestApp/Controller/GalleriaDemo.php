<?php

namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\Asset\AssetLoader;

class GalleriaDemo extends \Phifty\Controller
{
    function run()
    {
		$widget = AssetLoader::load( 'Galleria' );
        // return $this->render( 'TestApp/template/galleria_demo.html' , array( 'Galleria' => $widget ) );
    }
}

