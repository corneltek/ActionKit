<?php

namespace Core\Controller;

use Phifty\Controller;

class Index extends \Phifty\Controller
{
    function run()
    {
        return $this->render( 'Core/template/index.html' );
    }
}

?>
