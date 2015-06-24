<?php
namespace ActionKit\ActionTemplate; 
use ActionKit\ActionRunner; 
use Exception;
use ActionKit\Exception\UnableToWriteCacheException;
use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 *  File-Based Action Template Synopsis
 *    To generate from template file
 *
 *    $generator = new ActionGenerator();
 *
 *    // register template to generator
 *    $generator->registerTemplate(new FileBasedActionTemplate('FileBasedActionTemplate', array('cache_dir' => 'cache1')));
 *
 *    // load template by name
 *    $template = $generator->loadTemplate('FileBasedActionTemplate');
 *
 *    $runner = new ActionKit\ActionRunner;
 *    // register action to template
 *    $template->register($runner, array(
 *        'targetClassName' => 'User\\Action\\BulkUpdateUser',
 *        'templateName' => '@ActionKit/RecordAction.html.twig',
 *        'variables' => array(
 *             'record_class' => 'User\\Model\\User',
 *             'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
 *        )
 *    ));
 *    $className = 'User\Action\BulkUpdateUser';
 *
 *    // generate action from template
 *    $cacheFile = $generator->generate('FileBasedActionTemplate', 
 *        $className, 
 *        $runner->dynamicActions[$className]['actionArgs']);
 *
 *    require $cacheFile;
 *
 *
 * Depends on Twig template engine
 */

class FileBasedActionTemplate implements ActionTemplate
{
    public $name;
    private $cacheDir;
    private $templateDirs = array();

    public function __construct($templateName, array $options = array() )
    {
        $this->name = $templateName;
        if ( isset($options['cache_dir']) ) {
            $this->cacheDir = $options['cache_dir'];
        } else {
            $this->cacheDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Cache';
            if (! file_exists($this->cacheDir)) {
                mkdir($this->cacheDir, 0755, true);
            }
        }
    }

    /**
     *  @synopsis
     *
     *      $template->register($runner, array(
     *          'targetClassName' => 'User\\Action\\BulkUpdateUser',
     *          'templateName' => '@ActionKit/RecordAction.html.twig',
     *          'variables' => array(
     *              'record_class' => 'User\\Model\\User',
     *              'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
     *          )
     *      ));
     */
    public function register(ActionRunner $runner, array $options = array())
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

        $runner->register( $class, $this->getTemplateName(), [
            'template' => $templateName,
            'variables' => $variables
        ]);
    }
    
    /**
     * @synopsis
     *     $cacheFile = $generator->generate('FileBasedActionTemplate',
     *          'User\Action\BulkUpdateUser',
     *          [
     *              'template' => '@ActionKit/RecordAction.html.twig',
     *              'variables' => array(
     *                  'record_class' => 'User\\Model\\User',
     *                  'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
     *              )
     *          ]);
     */
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

    function getTemplateName()
    {
        return $this->name;
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