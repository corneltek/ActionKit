<?php

class TestApp extends \Phifty\MicroApp
{
    function init()
    {
        $this->route( '/html5upload' , array( 'controller' => '\TestApp\Controller\Html5Upload' ) );
        $this->route( '/foo' , array( 'template' => array( 
            'template' => 'foo.html',
            'engine' => 'twig' )) 
        );
        $this->route( '/bar' , 'Bar' );
    }
}

?>
