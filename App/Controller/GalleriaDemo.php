<?php

namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\WidgetLoader;

class GalleriaDemo extends \Phifty\Controller
{
    function run()
    {
		$widget = WidgetLoader::load( 'Galleria' );
		$widget->addImage( $widget->baseUrl() . '/images/1.jpg' );
		$widget->addImage( $widget->baseUrl() . '/images/2.jpg' );
		$widget->addImage( $widget->baseUrl() . '/images/3.jpg' );
        return $this->render( 'App/template/galleria_demo.html' , array( 'Galleria' => $widget ) );
    }
}

?>
