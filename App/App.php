<?php

class App extends \Phifty\MicroApp
{
    function init()
    {
        $this->route( '/foo' , array( 'template' => array( 
            'template' => 'testapp/template/foo.html',
            'engine' => 'twig' )) 
        );
        $this->route( '/twig_test' , array( 'template' => array( 'template' => 'testapp/template/twig_test.html' , 'engine' => 'twig' ) ) );
        $this->route( '/bar' , 'Bar' );
        $this->route( '/galleria_demo' , 'GalleriaDemo' );   // Controller\GalleriaDemo
        $this->route( '/pretty_photo' , 'PrettyPhoto' );     // Controller\PrettyPhoto
    }
}

?>
