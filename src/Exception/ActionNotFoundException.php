<?php
namespace ActionKit\Exception;

use LogicException;

class ActionNotFoundException extends LogicException
{
    public $actionClass;

    public function __construct($actionClass)
    {
        $this->actionClass = $actionClass;
        parent::__construct("Action class '$actionClass' not found, you might need to setup action autoloader or register the action template");
    }
}
