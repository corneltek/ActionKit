<?php

namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\WidgetLoader;

class PrettyPhoto extends \Phifty\Controller
{
    function run()
    {
		$widget = WidgetLoader::load('PrettyPhoto');
        return $this->render( 'pretty_photo.html' , array( "PrettyPhoto" => $widget ) );
    }
}

?>
