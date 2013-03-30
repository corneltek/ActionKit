<?php
namespace ActionKit\View;
use ReflectionObject;
use Exception;
use Twig_Loader_Filesystem;
use Twig_Environment;

abstract class TemplateView
{
    private $_classDir;

    public function getClassDir()
    {
        if ( $this->_classDir ) {
            return $this->_classDir;
        }
        $ref = new ReflectionObject($this);
        return $this->_classDir = dirname($ref->getFilename());
    }

    public function getTemplateDir()
    {
        return $this->getClassDir() . DIRECTORY_SEPARATOR . 'Templates';
    }

    public function createTwigFileSystemLoader()
    {
        $dir = $this->getTemplateDir();
        if ( ! file_exists($dir) ) {
            throw RuntimeException("Directory $dir for TemplateView does not exist.");
        }
        return new Twig_Loader_Filesystem($dir);
    }

    public function createTwigEnvironment()
    {
        $loader = $this->createTwigFileSystemLoader();
        return new Twig_Environment($loader, $this->getTwigConfig());
    }

    public function getTwigConfig()
    {
        return array();
    }

    public function getTemplate($templateFile)
    {
        $twig = $this->createTwigEnvironment();
        return $twig->loadTemplate($templateFile);
    }

    /* $twig->render('index.html', array('the' => 'variables', 'go' => 'here')); */
    public function renderTemplateFile($templateFile,$arguments = array())
    {
        $template = $this->getTemplate($templateFile);
        return $template->render($arguments);
    }

    abstract public function render();
}

