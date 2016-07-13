<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\GeneratedAction;
use ActionKit\Exception\RequiredConfigKeyException;
use Exception;
use ClassTemplate\TemplateClassFile;

/**
 *  CodeGen-Based Action Template Synopsis
 *
 *      $actionTemplate = new CodeGenActionTemplate();
 *      $runner = new ActionKit\ActionRunner;
 *      $actionTemplate->register($runner, 'CodeGenActionTemplate', array(
 *          'namespace' => 'test2',
 *          'model' => 'test2Model',   // model's name
 *          'types' => array('Create','Update','Delete','BulkDelete')
 *      ));
 *
 *      $className = 'test2\Action\UpdatetestModel';
 *      $generatedAction = $actionTemplate->generate($className, [
 *          'extends' => "\\ActionKit\\RecordAction\\CreateRecordAction",
 *          'properties' => [
 *              'recordClass' => "test2\\Model\\testModel",
 *          ],
 *      ]);
 *
 *      $generatedAction->requireAt($cacheCodePath);
 *
 */
class CodeGenActionTemplate implements ActionTemplate
{
    /**
     * @synopsis
     *
     *    $template->register($runner, [
     *       'action_class' => 'FooAction',
     *       'extends' => "\\ActionKit\\RecordAction\\{$type}RecordAction",
     *       'properties' => [
     *           'recordClass' => $options['namespace'] . "\\Model\\" . $options['model'],
     *       ],
     *    ]);
     */
    public function register(ActionRunner $runner, $asTemplate, array $options = array())
    {
        if (isset($options['use'])) {
            array_unshift($options['use'], '\\ActionKit\\Action');
        } else {
            $options['use'] = ['\\ActionKit\\Action'];
        }
        $runner->register($options['action_class'], $asTemplate, $options);
    }

    public function initGenericClassWithOptions(TemplateClassFile $templateClassFile, array $options = array()) 
    {
        if (isset($options['use'])) {
            foreach( $options['use'] as $use ) {
                $templateClassFile->useClass($use);
            }
        }
        if (isset($options['extends'])) {
            $templateClassFile->extendClass($options['extends']);
        }
        if (isset($options['properties'])) {
            foreach( $options['properties'] as $name => $value ) {
                $templateClassFile->addProperty($name, $value);
            }
        }
        if (isset($options['constants'])) {
            foreach( $options['constants'] as $name => $value ) {
                $templateClassFile->addConst($name, $value);
            }
        }
        if (isset($options['traits'])) {
            foreach( $options['traits'] as $traitClass ) {
                $templateClassFile->useTrait($traitClass);
            }
        }
    }

    public function generate($actionClass, array $options = array())
    {
        $templateClassFile = new TemplateClassFile($actionClass);
        $this->initGenericClassWithOptions($templateClassFile, $options);
        $code = $templateClassFile->render();
        return new GeneratedAction($actionClass, $code, $templateClassFile);
    }
    
}
