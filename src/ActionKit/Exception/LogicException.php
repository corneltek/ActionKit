<?php
namespace ActionKit\Exception;
use Exception;

class LogicException extends Exception
{
    public function __construct( $msg)
    {
        parent::__construct($msg);
    }
}
