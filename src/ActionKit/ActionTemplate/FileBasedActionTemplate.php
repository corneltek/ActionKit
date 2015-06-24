<?php
namespace ActionKit\ActionTemplate; 
use ActionKit\ActionRunner;
use ActionKit\GeneratedAction;
use ActionKit\Exception\RequiredConfigKeyException;
use Twig_Loader_Filesystem;
use Twig_Environment;
use ReflectionClass;

/**
 *  File-Based Action Template Synopsis
 *    To generate from template file
 *
 *    $actionTemplate = new FileBasedActionTemplate();
 *
 *    $runner = new ActionKit\ActionRunner;
 *    $actionTemplate->register($runner, 'FileBasedActionTemplate', array(
 *        'targetClassName' => 'User\\Action\\BulkUpdateUser',
 *        'template' => '@ActionKit/RecordAction.html.twig',
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
    private $templateDirs = array();

    protected $loader;

    protected $env;

    public function __construct(Twig_Loader_Filesystem $loader = null, Twig_Environment $env = null)
    {
        if (!$loader) {
            $refClass = new ReflectionClass('ActionKit\\ActionGenerator');
            $templateDirectory = dirname($refClass->getFilename()) . DIRECTORY_SEPARATOR . 'Templates';

            // add ActionKit built-in template path
            $loader = new Twig_Loader_Filesystem([]);
            $loader->addPath($templateDirectory, 'ActionKit');
        }
        $this->loader = $loader;
        if (!$env) {
            $env = new Twig_Environment($this->loader, array(
                'cache' => false,
            ));
        }
        $this->env = $env;
    }

    /**
     *  @synopsis
     *
     *      $template->register($runner,
     *          'templateName',
     *          array(
     *              'targetClassName' => 'User\\Action\\BulkUpdateUser',
     *              'template' => '@ActionKit/RecordAction.html.twig',
     *              'variables' => array(
     *                  'record_class' => 'User\\Model\\User',
     *                  'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
     *              )
     *      ));
     */
    public function register(ActionRunner $runner, $asTemplate, array $options = array())
    {
        // $targetActionClass, $template, $variables
        if (!isset($options['targetClassName'])) {
            throw new RequiredConfigKeyException('targetClassName');
        }
        $class = $options['targetClassName'];

        if (!isset($options['template'])) {
            throw new RequiredConfigKeyException('template');
        }
        $template = $options['template'];

        if (!isset($options['variables'])) {
            throw new RequiredConfigKeyException('variables');
        }
        $variables = $options['variables'];

        $runner->register( $class, $asTemplate, [
            'template' => $template,
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
        if (!isset($options['template'])) {
            throw new RequiredConfigKeyException('template is not defined.');
        }
        $template = $options['template'];

        if (!isset($options['variables'])) {
            throw new RequiredConfigKeyException('variables is not defined.');
        }
        $variables = $options['variables'];

        $parts = explode("\\",$targetClassName);
        $variables['target'] = array();
        $variables['target']['classname'] = array_pop($parts);
        $variables['target']['namespace'] = join("\\", $parts);
        $code = $this->env->render($template, $variables);
        return new GeneratedAction($targetClassName, $code);
    }

    public function getTwigEnvironment() 
    {
        return $this->env;
    }

    public function getTwigLoader() 
    {
        return $this->loader;
    }
}
