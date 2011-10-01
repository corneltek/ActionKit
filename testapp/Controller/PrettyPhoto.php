<?php

namespace TestApp\Controller;

use Phifty\Controller;

class PrettyPhoto extends \Phifty\Controller
{
    function run()
    {
        return $this->render( 'pretty_photo.html' );
    }
}

?>
