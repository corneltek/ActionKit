<?php
namespace ActionKit;
use ReflectionObject;
use Exception;
use Twig_Loader_Filesystem;
use Twig_Environment;

class Template
{
    public $_classDir;

    public $loader;

    public $environment;

    public $config;

    public function __construct($config = array()) 
    {
        $this->config = $config;
    }

    public function init() {
        $dir = $this->getTemplateDir();
        if ( ! file_exists($dir) ) {
            throw RuntimeException("Directory $dir for TemplateView does not exist.");
        }
        $this->loader =  new Twig_Loader_Filesystem($dir);
        $this->environment = new Twig_Environment($this->loader, $this->config);
    }

    public function setClassDirFrom($object)
    {
        $ref = new ReflectionObject($object);
        return $this->_classDir = dirname($ref->getFilename());
    }

    public function getClassDir()
    {
        if ( $this->_classDir ) {
            return $this->_classDir;
        }
        return $this->setClassDirFrom($this);
    }

    public function getTemplateDir()
    {
        return $this->getClassDir() . DIRECTORY_SEPARATOR . 'Templates';
    }


    /**
     * $template->render('@ActionKit/index.html', array('the' => 'variables', 'go' => 'here')); 
     */
    public function render($templateFile, $arguments = array())
    {
        $template = $this->environment->loadTemplate($templateFile);
        return $template->render($arguments);
    }
}

