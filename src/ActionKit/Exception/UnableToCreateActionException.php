<?php
namespace ActionKit\Exception;
use Exception;

class UnableToCreateActionException extends Exception
{

    public function __construct( $msg )
    {
        parent::__construct($msg);
    }
}
