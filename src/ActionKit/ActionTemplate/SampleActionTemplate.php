<?php
namespace ActionKit\ActionTemplate;
use ClassTemplate\TemplateClassFile;

/**
 *  Sample Action Template Synopsis
 *
 *      $actionTemplate = new SampleActionTemplate('SampleActionTemplate');
 *      $template = $actionTemplate->generate('', '', array(
 *          'namespaceName' => 'Core',
 *          'actionName' => 'GrantAccess'
 *      ));
 *
 *      $template->load();
 */
class SampleActionTemplate extends CodeGenActionTemplate
{
    public function generate($targetClassName, $cacheFile, array $options = array())
    {
        if ( isset($options['namespaceName'])) {
            $namespaceName = $options['namespaceName'];
        }

        if ( isset($options['actionName'])) {
            $actionName = $options['actionName'];
        }

        $targetClassName = "$namespaceName\\Action\\$actionName";
        $options = [ 
            'extends' => 'Action',
            'getTemplateClass' => true
        ];

        $templateClassFile = new TemplateClassFile($targetClassName);

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
        
        return $templateClassFile;
    }
}