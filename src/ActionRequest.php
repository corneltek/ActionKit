<?php
namespace ActionKit;

use ActionKit\Utils;
use Universal\Http\HttpRequest;
use Universal\Http\FilesParameter;

class ActionRequest extends HttpRequest
{
    protected $ajax = false;

    protected $arguments = array();

    protected $actionName;

    /**
     * @var array[Universal\Http\UploadedFile] the request-wide file objects.
     */
    protected $uploadedFiles = array();


    public function __construct(array $requestParameters = array(), array $files = array())
    {
        parent::__construct($requestParameters, $files);

        // Copy the request parameters to arguments, we are going to remove some fields.
        $this->arguments = array_merge($this->parameters, array());

        if (isset($this->arguments['__ajax_request'])) {
            unset($this->arguments['__ajax_request']);
            $this->ajax = true;
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




    /**
     * isInvalidActionName returns int
     *
     * @return integer matched count.
     */
    public function isInvalidActionName()
    {
        return preg_match('/[^A-Za-z0-9:]/i', $this->actionName);
    }

    public function isFullQualifiedName()
    {
        return strpos($this->actionName, '::') !== false;
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

    public static function hasAction(array $requestParameters = array())
    {
        return isset($requestParameters['__action']);
    }




    // XXX: the uploadedFile methods should be not used.
    /**
     * This is a simple uploaded file storage, it doesn't support multiple files
     */
    public function uploadedFile($fieldName, $index = 0)
    {
        if (isset($this->uploadedFiles[$fieldName][$index])) {
            return $this->uploadedFiles[$fieldName][$index];
        }
    }

    public function saveUploadedFile($fieldName, $index, $file)
    {
        return $this->uploadedFiles[$fieldName][$index] = $file;
    }
}
