<?php
namespace ActionKit\ActionTemplate;
use ActionKit\GeneratedAction;
use ClassTemplate\ClassFile;
use ActionKit\Exception\RequiredConfigKeyException;

/**
 *  Sample Action Template Synopsis
 *
 *      $actionTemplate = new SampleActionTemplate('SampleActionTemplate');
 *      $generatedAction = $actionTemplate->generate('', array(
 *          'namespace' => 'Core',
 *          'action_name' => 'GrantAccess'
 *      ));
 *
 *      $generatedAction->requireAt($cacheFilePath);
 */
class SampleActionTemplate extends CodeGenActionTemplate
{
    public function generate($actionClass, array $options = array())
    {
        if (! isset($options['namespace'])) {
            throw new RequiredConfigKeyException('action_name');
        }

        if (!isset($options['action_name'])) {
            throw new RequiredConfigKeyException('action_name');
        }

        $namespace = $options['namespace'];
        $actionName = $options['action_name'];

        $actionClass = "$namespace\\Action\\$actionName";
        $options = [ 'extends' => 'Action', ];

        $class = $this->createActionClassFile($actionClass, $options);

        // General use statement
        $class->useClass('\\ActionKit\\Action');
        $class->useClass('\\ActionKit\\RecordAction\\BaseRecordAction');


        $class->addMethod('public','schema', [] , '');
        $class->addMethod('public','run', [] , 'return $this->success("Success!");');
        
        $code = $class->render();
        return new GeneratedAction($actionClass, $code, $class);
    }
}
