<?php
namespace ActionKit\Exception;
use Exception;

class ActionException extends Exception
{
    public $action;

    public function __construct($msg, $action)
    {
        $this->action = $action;
        parent::__construct($msg);
    }
}
