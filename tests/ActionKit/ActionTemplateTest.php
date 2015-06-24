<?php
use ActionKit\ActionTemplate\SampleActionTemplate;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\ActionTemplate\FileBasedActionTemplate;

class ActionTemplate extends PHPUnit_Framework_TestCase
{
    public function testSampleActionTemplate()
    {
        $actionTemplate = new SampleActionTemplate();
        $generatedAction = $actionTemplate->generate('', array(
            'namespaceName' => 'Core',
            'actionName' => 'GrantAccess'
        ));
        ok( $generatedAction );

        $temp = str_replace('\\', DIRECTORY_SEPARATOR, ltrim($generatedAction->className,'\\'));
        $tmpname = tempnam('/tmp', $temp);
        $generatedAction->requireAt($tmpname);

        is( 'Core\\Action\\GrantAccess' , $generatedAction->className );
        ok( class_exists( 'Core\\Action\\GrantAccess' ) );
    }

    public function testCodeGenBased()
    {
        $actionTemplate = new CodeGenActionTemplate();
        $runner = new ActionKit\ActionRunner;
        $actionTemplate->register($runner, 'CodeGenActionTemplate', array(
            'namespace' => 'test2',
            'model' => 'test2Model',   // model's name
            'types' => array('Create','Update','Delete','BulkDelete')
        ));
        is(4, count($runner->dynamicActions));

        $className = 'test2\Action\Updatetest2Model';
        $generatedAction = $actionTemplate->generate($className, $runner->dynamicActions[$className]);
        ok( $generatedAction );

        $temp = str_replace('\\', DIRECTORY_SEPARATOR, ltrim($generatedAction->className,'\\'));
        $tmpname = tempnam('/tmp', $temp);
        $generatedAction->requireAt($tmpname);
        ok( class_exists( $className ) );
    }

    public function testFildBased()
    {
        $actionTemplate = new FileBasedActionTemplate();

        $runner = new ActionKit\ActionRunner;
        $actionTemplate->register($runner, 'FileBasedActionTemplate', array(
            'targetClassName' => 'User\\Action\\BulkUpdateUser',
            'templateName' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        is(1, count($runner->dynamicActions));

        $className = 'User\Action\BulkUpdateUser';

        is(true, isset($runner->dynamicActions[$className]));

        $generatedAction = $actionTemplate->generate($className, 
            $runner->dynamicActions[$className]['actionArgs']);
        ok($generatedAction);

        $temp = str_replace('\\', DIRECTORY_SEPARATOR, ltrim($generatedAction->className,'\\'));
        $tmpname = tempnam('/tmp', $temp);
        $generatedAction->requireAt($tmpname);
        ok( class_exists( $className ) );
    }
}
