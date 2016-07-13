<?php
namespace ActionKit\Exception;
use Exception;
use ActionKit\Action;

class ActionException extends Exception
{
    public $action;

    public function __construct($msg, Action $action = null)
    {
        $this->action = $action;
        parent::__construct($msg);
    }
}
