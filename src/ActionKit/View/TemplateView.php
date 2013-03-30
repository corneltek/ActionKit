<?php
namespace ActionKit\View;
use ReflectionObject;
use Twig_Loader_Filesystem;
use Twig_Environment;

class TemplateView
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
        return new Twig_Loader_Filesystem($this->getTemplateDir());
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
        $template = $twig->loadTemplate($templateFile);
        return $template;
    }

    /* $twig->render('index.html', array('the' => 'variables', 'go' => 'here')); */
    public function renderTemplate($template,$arguments = array())
    {
        return $template->render($arguments);
    }

}



