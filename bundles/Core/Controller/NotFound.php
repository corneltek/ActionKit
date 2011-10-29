<?php
namespace Core\Controller;

use Phifty\Controller;

class NotFound extends \Phifty\Controller
{
    function run()
    {
        return $this->render( 'Core/template/not_found.html' );
    }
}

?>
