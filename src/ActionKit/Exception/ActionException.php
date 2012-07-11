<?php
namespace ActionKit\Exception;

class ActionException extends Exception
{
    public $action;

    function __construct( $msg , $action ) {
        $this->action = $action;
        parent::__construct($msg);
    }
}

