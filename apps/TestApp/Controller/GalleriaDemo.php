<?php

namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\AssetLoader;

class GalleriaDemo extends \Phifty\Controller
{
    function run()
    {
		$widget = AssetLoader::load( 'Galleria' );
		$widget->addImage( $widget->baseUrl() . '/images/1.jpg' );
		$widget->addImage( $widget->baseUrl() . '/images/2.jpg' );
		$widget->addImage( $widget->baseUrl() . '/images/3.jpg' );
        return $this->render( 'TestApp/template/galleria_demo.html' , array( 'Galleria' => $widget ) );
    }
}

?>
