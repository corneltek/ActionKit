<?php
namespace ActionKit\ActionTemplate;  
use Exception;
use ActionKit\Exception\UnableToWriteCacheException;
use Twig_Loader_Filesystem;
use Twig_Environment;

class FileActionTemplate
{
    private $cacheDir;
    private $templateDirs = array();

    public function __construct(array $options = array() )
    {
        if ( isset($options['cache_dir']) ) {
            $this->cacheDir = $options['cache_dir'];
        } else {
            $this->cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
            if (! file_exists($this->cacheDir)) {
                mkdir($this->cacheDir, 0755, true);
            }
        }
    }

    public function register($runner, array $options = array())
    {
        // $targetActionClass, $template, $variables
        if ( isset($options['targetClassName'])) {
            $class = $options['targetClassName'];
        } else {
            throw new Exception('targetClassName is not defined.');
        } 

        if ( isset($options['templateName'])) {
            $templateName = $options['templateName'];
        } else {
            throw new Exception('templateName is not defined.');
        }

        if ( isset($options['variables'])) {
            $variables = $options['variables'];
        } else {
            throw new Exception('variables is not defined.');
        }

        $runner->registerWithTemplate( $class, $this->getTemplateName(), [
            'template' => $templateName,
            'variables' => $variables
        ]);
    }
    
    public function generate($targetClassName, $cacheFile, array $options = array())
    {
        if ( isset($options['template'])) {
            $template = $options['template'];
        } else {
            throw new Exception('template is not defined.');
        }
        if ( isset($options['variables'])) {
            $variables = $options['variables'];
        } else {
            throw new Exception('variables is not defined.');
        }

        $parts = explode("\\",$targetClassName);
        $variables['target'] = array();
        $variables['target']['classname'] = array_pop($parts);
        $variables['target']['namespace'] = join("\\", $parts);
        $twig = $this->getTwig();
        $code = $twig->render($template, $variables);

        if ( false === file_put_contents($cacheFile, $code) ) {
            throw new UnableToWriteCacheException("Can not write action class cache file: $cacheFile");
        }
        return $cacheFile;
    }

    public function getTemplateName()
    {
        return 'FileActionTemplate';
    }

    public function addTemplateDir($path)
    {
        $this->templateDirs[] = $path;
    }

    public function getTwigLoader() {

        static $loader;
        if ( $loader ) {
            return $loader;
        }
        // add ActionKit built-in template path
        $loader = new Twig_Loader_Filesystem($this->templateDirs);
        $loader->addPath( __DIR__ . DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . 'Templates', "ActionKit" );
        return $loader;
    }

    private function getTwig()
    {
        static $twig;
        if ( $twig ) {
            return $twig;
        }
        $loader = $this->getTwigLoader();
        $env = new Twig_Environment($loader, array(
            'cache' => $this->cacheDir ? $this->cacheDir : false,
        ));
        return $env;
    }
}