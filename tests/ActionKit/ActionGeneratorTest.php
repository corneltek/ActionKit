<?php

class ActionGeneratorTest extends PHPUnit_Framework_TestCase
{


    public function testCRUDClassFromBaseRecordAction()
    {
        $class = ActionKit\RecordAction\BaseRecordAction::createCRUDClass( 'App\Model\Post' , 'Create' );
        ok($class);
        is('App\Action\CreatePost', $class);
    }

    public function testCodeGenBased()
    {
        $generator = new ActionKit\ActionGenerator();
        $generator->registerTemplate('CodeGenActionTemplate', new ActionKit\ActionTemplate\CodeGenActionTemplate());
        $template = $generator->loadTemplate('CodeGenActionTemplate');
        ok($template);

        $runner = new ActionKit\ActionRunner;
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array('Create','Update','Delete','BulkDelete')
        );
        $template->register($runner, 'CodeGenActionTemplate', $actionArgs);
        is(4, count($runner->dynamicActions));

        $className = 'test\Action\UpdatetestModel';

        is(true, isset($runner->dynamicActions[$className]));

            $generator->generate('CodeGenActionTemplate',
            $className,
            $runner->dynamicActions[$className]['actionArgs']);

        ok( class_exists( $className ) );
    }

    public function testFildBased()
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

        $generator->generate('FileBasedActionTemplate',
            $className,
            $runner->dynamicActions[$className]['actionArgs']);

        ok( class_exists( $className ) );
    }

    public function testWithoutRegister()
    {
        $generator = new ActionKit\ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());

        $className = 'User\Action\BulkDeleteUser';

        $generator->generate('FileBasedActionTemplate',
            $className,
            array(
                'template' => '@ActionKit/RecordAction.html.twig',
                'variables' => array(
                    'record_class' => 'User\\Model\\User',
                    'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
                )
            )
        );

        ok( class_exists( $className ) );
    }

}

