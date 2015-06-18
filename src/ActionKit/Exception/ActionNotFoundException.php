<?php
namespace ActionKit\Exception;
use Exception;

class ActionNotFoundException extends Exception
{

    public function __construct( $msg )
    {
        parent::__construct($msg);
    }
}
