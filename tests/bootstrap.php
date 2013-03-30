<?php
$loader = require 'vendor/autoload.php';
$loader->add(null,'tests');

function __() 
{
    $args = func_get_args();
    $msg = _( array_shift( $args ) );
    $id = 1;
    foreach ($args as $arg) {
        $msg = str_replace( "%$id" , $arg , $msg );
        $id++;
    }
    return $msg;
}
