<?php
namespace ActionKit;
use RuntimeException;
use Twig_Loader_Filesystem;
use Twig_Loader_String;
use Twig_Environment;

/**
 * A simple class wrapper for Twig String Loader
 */
class StringTemplate
{

    public $template;
    public $loader;
    public $environment;

    public function __construct($str, $config = array()) {
        $this->loader = new Twig_Loader_String;
        $this->environment = new Twig_Environment($this->loader, $config);
        $this->template = $this->environment->loadTemplate($str);
    }

    public function render($arguments = array() ) 
    {
        return $this->template->render($arguments);
    }
}



