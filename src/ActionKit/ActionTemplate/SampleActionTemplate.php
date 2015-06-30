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
 *          'actionName' => 'GrantAccess'
 *      ));
 *
 *      $generatedAction->requireAt($cacheFilePath);
 */
class SampleActionTemplate extends CodeGenActionTemplate
{
    public function generate($actionClass, array $options = array())
    {
        if ( isset($options['namespace'])) {
            $namespace = $options['namespace'];
        }

        if ( isset($options['actionName'])) {
            $actionName = $options['actionName'];
        }

        $actionClass = "$namespace\\Action\\$actionName";
        $options = [ 
            'extends' => 'Action',
        ];
        $templateClassFile = new TemplateClassFile($actionClass);

        // General use statement
        $templateClassFile->useClass('\\ActionKit\\Action');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\BaseRecordAction');

        if ( isset($options['extends']) ) {
            $templateClassFile->extendClass($options['extends']);
        }
        if ( isset($options['properties']) ) {
            foreach( $options['properties'] as $name => $value ) {
                $templateClassFile->addProperty($name, $value);
            }
        }
        if ( isset($options['constants']) ) {
            foreach( $options['constants'] as $name => $value ) {
                $templateClassFile->addConst($name, $value);
            }
        }

        $templateClassFile->addMethod('public','schema', [] , '');
        $templateClassFile->addMethod('public','run', [] , 'return $this->success("Success!");');
        
        $code = $templateClassFile->render();
        return new GeneratedAction($actionClass, $code, $templateClassFile);
    }
}
