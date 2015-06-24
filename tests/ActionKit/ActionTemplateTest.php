<?php

class ActionTemplate extends PHPUnit_Framework_TestCase
{
    protected $dynamicActions = array();

    public function testCodeGenBased()
    {
        $generator = new ActionKit\ActionGenerator();
        $generator->registerTemplate('CodeGenActionTemplate', new ActionKit\ActionTemplate\CodeGenActionTemplate());
        $template = $generator->loadTemplate('CodeGenActionTemplate'); 
        ok($template);

        $runner = new ActionKit\ActionRunner;
        $template->register($runner, 'CodeGenActionTemplate', array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array('Create','Update','Delete','BulkDelete')
        ));
        is(4, count($runner->dynamicActions));

        $className = 'test\Action\UpdatetestModel';

        is(true, isset($runner->dynamicActions[$className]));

        $cacheFile = $generator->generate('CodeGenActionTemplate', 
            $className, 
            $runner->dynamicActions[$className]['actionArgs']);

        require $cacheFile;
        ok( class_exists( $className ) );
    }

    public function testTemplateBased()
    {
        $generator = new ActionKit\ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());
        $template = $generator->loadTemplate('FileBasedActionTemplate'); 
        ok($template);

        $runner = new ActionKit\ActionRunner;
        $template->register($runner, 'FileBasedActionTemplate', array(
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

        $cacheFile = $generator->generate('FileBasedActionTemplate', 
            $className, 
            $runner->dynamicActions[$className]['actionArgs']);

        require $cacheFile;
        ok( class_exists( $className ) );
    }

    public function testWithRegister()
    {
        $generator = new ActionKit\ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());
        
        $className = 'User\Action\BulkDeleteUser';

        $cacheFile = $generator->generate('FileBasedActionTemplate', 
            $className, 
            array(
                'template' => '@ActionKit/RecordAction.html.twig',
                'variables' => array(
                    'record_class' => 'User\\Model\\User',
                    'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
                )
            )
        );

        require $cacheFile;
        ok( class_exists( $className ) );
    }
}