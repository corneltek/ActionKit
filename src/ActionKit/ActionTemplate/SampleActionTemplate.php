<?php
namespace ActionKit\ActionTemplate;
use ActionKit\GeneratedAction;
use ClassTemplate\TemplateClassFile;
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
        $options = [ 
            'extends' => 'Action',
        ];
        $templateClassFile = new TemplateClassFile($actionClass);

        // General use statement
        $templateClassFile->useClass('\\ActionKit\\Action');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\BaseRecordAction');

        $this->initGenericClassWithOptions($templateClassFile, $options);

        $templateClassFile->addMethod('public','schema', [] , '');
        $templateClassFile->addMethod('public','run', [] , 'return $this->success("Success!");');
        
        $code = $templateClassFile->render();
        return new GeneratedAction($actionClass, $code, $templateClassFile);
    }
}
