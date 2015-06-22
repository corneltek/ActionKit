<?php
namespace ActionKit\Exception;
use Exception;

class UnableToWriteCacheException extends Exception
{

    public function __construct( $msg )
    {
        parent::__construct($msg);
    }
}
