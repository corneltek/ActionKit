<?php

namespace TestApp;

class Application extends \Phifty\MicroApp
{
    function init()
    {
        $this->route( '/foo' , array( 'template' => array( 
            'template' => 'TestApp/template/foo.html',
            'engine' => 'twig' )) 
        );
        $this->route( '/twig_test' , array( 'template' => 'TestApp/template/twig_test.html' ) );
        $this->route( '/bar' , 'Bar' );
        $this->route( '/galleria_demo' , 'GalleriaDemo' );   // Controller\GalleriaDemo
        $this->route( '/pretty_photo' , 'PrettyPhoto' );     // Controller\PrettyPhoto

        # front end unit testing
        $this->route( '/tests/action' , 'ActionUnitTest' );
        $this->route( '/tests/qunit'  , 'QUnitTest' );
    }
}

