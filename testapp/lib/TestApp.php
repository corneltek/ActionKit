<?php

class TestApp extends \Phifty\MicroApp
{
    function init()
    {
        $this->route( '/foo' , array( 'template' => array( 
            'template' => 'foo.html',
            'engine' => 'twig' )) 
        );
        $this->route( '/twig_test' , array( 'template' => array( 'template' => 'twig_test.html' , 'engine' => 'twig' ) ) );
        $this->route( '/bar' , 'Bar' );
    }
}

?>
