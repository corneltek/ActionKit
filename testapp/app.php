<?php

class TestApp extends \Phifty\MicroApp
{
    function init()
    {
        $this->route( '/foo' , array( 'template' => array( 
            'template' => 'foo.html',
            'engine' => 'twig' )) 
        );
        $this->route( '/bar' , 'Bar' );
    }
}

?>
