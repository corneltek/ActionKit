<?php
namespace ActionKit\Exception;
use Exception;

class UndefinedTemplateException extends Exception
{
    public function __construct( $msg)
    {
        parent::__construct($msg);
    }
}
