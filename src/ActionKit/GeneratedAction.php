<?php
namespace ActionKit;
use ActionKit\Exception\UnableToWriteCacheException;

class GeneratedAction
{
    public $className; 
    public $code; 
    public $object;

    public function __construct($className, $code, $object = null)
    {
        $this->className = $className;
        $this->code = $code;
        $this->object = $object;
    }

    public function requireAt($path)
    {
        $this->writeTo($path);
        require $path;
    }

    public function writeTo($path)
    {
        if (false === file_put_contents($path, $this->code)) {
            throw new UnableToWriteCacheException("Can not write action class cache file: $cacheFile");
        }
    }

    public function load()
    {
        $tmpname = tempnam('/tmp', md5($this->className));
        $this->requireAt($tmpname);
        return $tmpname;
    }

    public function getPsrClassPath()
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, ltrim($this->className,'\\'));
    }
}
