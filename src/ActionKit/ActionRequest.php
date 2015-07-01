<?php
namespace ActionKit;
use ActionKit\Utils;
use Universal\Http\HttpRequest;
use Universal\Http\FilesParameter;

class ActionRequest {

    protected $ajax = false;

    protected $requestParameters = array();

    protected $arguments = array();

    protected $files = array();

    protected $actionName;

    public function __construct(array $requestParameters = array(), array $files = null)
    {
        $this->requestParameters = $requestParameters;

        $this->arguments = array_merge($this->requestParameters, array());

        if (isset($this->arguments['__ajax_request'])) {
            unset($this->arguments['__ajax_request']);
            $this->ajax = true;
        }

        if ($files) {
            $this->files = FilesParameter::fix_files_array($files);
        }

        unset($this->arguments['__action']);
        unset($this->arguments['action']);

        // handle actionName
        $actionKey = isset($requestParameters['__action']) ? '__action' : 'action';
        if (isset($requestParameters[$actionKey])) {
            $this->actionName = $requestParameters[$actionKey];
        }
    }

    public function arg($field)
    {
        if (isset($this->arguments[$field])) {
            return $this->arguments[$field];
        }
        return null;
    }

    public function getFiles() 
    {
        return $this->files;
    }

    public function file($field)
    {
        if (isset($this->files[$field])) {
            return $this->files[$field];
        }
        return null;
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
