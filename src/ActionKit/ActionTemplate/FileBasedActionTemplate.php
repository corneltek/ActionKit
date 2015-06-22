<?php
namespace ActionKit\ActionTemplate; 
use ActionKit\ActionRunner;
use ActionKit\GeneratedAction;
use Exception;
use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 *  File-Based Action Template Synopsis
 *    To generate from template file
 *
 *    $actionTemplate = new FileBasedActionTemplate();
 *
 *    $runner = new ActionKit\ActionRunner;
 *    $actionTemplate->register($runner, 'FileBasedActionTemplate', array(
 *        'targetClassName' => 'User\\Action\\BulkUpdateUser',
 *        'templateName' => '@ActionKit/RecordAction.html.twig',
 *        'variables' => array(
 *            'record_class' => 'User\\Model\\User',
 *            'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
 *        )
 *    ));
 *
 *    $className = 'User\Action\BulkUpdateUser';
 *
 *    $generatedAction = $actionTemplate->generate($className,
 *        $runner->dynamicActions[$className]['actionArgs']);
 *
 *    $generatedAction->requireAt($cacheCodePath);
 *
 *
 * Depends on Twig template engine
 */

class FileBasedActionTemplate implements ActionTemplate
{
    private $cacheDir;
    private $templateDirs = array();

    public function __construct(array $options = array() )
    {
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
     *      $template->register($runner,
     *          'templateName',
     *          array(
     *              'targetClassName' => 'User\\Action\\BulkUpdateUser',
     *              'templateName' => '@ActionKit/RecordAction.html.twig',
     *              'variables' => array(
     *                  'record_class' => 'User\\Model\\User',
     *                  'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
     *              )
     *      ));
     */
    public function register(ActionRunner $runner, $asTemplate, array $options = array())
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

        $runner->register( $class, $asTemplate, [
            'template' => $templateName,
            'variables' => $variables
        ]);
    }
    
    /**
     * @synopsis
     *     $generatedAction = $template->generate('User\Action\BulkUpdateUser',  // class name
     *          [
     *              'template' => '@ActionKit/RecordAction.html.twig',
     *              'variables' => array(
     *                  'record_class' => 'User\\Model\\User',
     *                  'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
     *              )
     *          ]);
     */
    public function generate($targetClassName, array $options = array())
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

        return new GeneratedAction($targetClassName, $code);
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