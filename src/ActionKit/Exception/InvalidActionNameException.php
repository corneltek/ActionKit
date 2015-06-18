<?php
namespace ActionKit\Exception;
use Exception;

class InvalidActionNameException extends Exception
{

    public function __construct( $msg )
    {
        parent::__construct($msg);
    }
}
