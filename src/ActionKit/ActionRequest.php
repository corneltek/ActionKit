<?php
namespace ActionKit;
use ActionKit\Utils;

class ActionRequest {

    protected $ajax = false;

    protected $request = array();

    protected $arguments = array();

    protected $actionName;

    public function __construct(array $request) 
    {
        $this->request = $request;

        $this->arguments = array_merge($this->request, array());

        if (isset($this->arguments['__ajax_request'])) {
            unset($this->arguments['__ajax_request']);
            $this->ajax = true;
        }

        unset($this->arguments['__action']);
        unset($this->arguments['action']);

        // handle actionName
        $actionKey = isset($request['__action']) ? '__action' : 'action';
        if (isset($request[$actionKey])) {
            $this->actionName = $request[$actionKey];
        }
    }

    /**
     * isInvalidActionName returns int
     *
     * @return integer matched count.
     */
    public function isInvalidActionName()
    {
        return preg_match( '/[^A-Za-z0-9:]/i' , $this->actionName);
    }

    public function isFullQualifiedName()
    {
        return strpos($this->actionName, '::' ) !== false;
    }


    public function isAjax()
    {
        return $this->ajax;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    public function getArguments() 
    {
        return $this->arguments;
    }

}
