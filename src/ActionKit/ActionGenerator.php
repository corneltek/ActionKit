<?php
namespace ActionKit;
use ActionKit\ActionTemplate;
use Exception;
use Exception\UndefinedTemplateException;
use UniversalCache;
use ReflectionClass;
use ClassTemplate\TemplateClassFile;

/**
 * Action Generator Synopsis
 * 
 *    $generator = new ActionGenerator;
 *    $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());
 *
 *    $className = 'User\Action\BulkDeleteUser';
 *    $generatedAction = $generator->generate('FileBasedActionTemplate', 
 *        $className, 
 *        array(
 *            'template' => '@ActionKit/RecordAction.html.twig',
 *            'variables' => array(
 *                'record_class' => 'User\\Model\\User',
 *                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
 *            )
 *        )
 *    );
 *
 *    require $cacheFile;
 *
 */
class ActionGenerator
{

    public $templates = array();

    /**
     * The new generate method to generate action class with action template
     */
    public function generate($templateName, $class, array $actionArgs = array())
    {
        $actionTemplate = $this->loadTemplate($templateName);
        $generatedAction = $actionTemplate->generate($class, $actionArgs);
        return $generatedAction;
    }

    /**
     * register action template
     * @param object $template the action template object
     */
    public function registerTemplate($templateName, ActionTemplate\ActionTemplate $template)
    {
        $this->templates[$templateName] = $template;
    }

    /**
     * load action template object with action template name
     * @param string $templateName the action template name
     * @return object action template object
     */
    public function loadTemplate($templateName)
    {
        if ( isset($this->templates[$templateName])) {
            return $this->templates[$templateName];
        } else {
            throw new UndefinedTemplateException("load $templateName template failed.");
        }
    }
}
