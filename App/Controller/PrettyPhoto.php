<?php

namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\WidgetLoader;

class PrettyPhoto extends \Phifty\Controller
{
    function run()
    {
		$widget = WidgetLoader::load('PrettyPhoto');
        foreach( range(1,5) as $i ) {
            $widget->addImage( array( 
                'image' => $widget->baseUrl() . '/images/fullscreen/'. $i .'.jpg', 
                'thumb' => $widget->baseUrl() . '/images/thumbnails/t_'. $i .'.jpg',
                'caption' => 'Caption ' . $i, 
                'alt' => 'Alt Text' . $i,
            ));
        }
        return $this->render( 'pretty_photo.html' , array( "PrettyPhoto" => $widget ) );
    }
}

?>
