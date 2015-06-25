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
        $generator->registerTemplate('RecordActionTemplate', new ActionKit\ActionTemplate\RecordActionTemplate());
        $template = $generator->loadTemplate('RecordActionTemplate');
        ok($template);

        $runner = new ActionKit\ActionRunner;
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array('Create','Update','Delete','BulkDelete')
        );
        $template->register($runner, 'RecordActionTemplate', $actionArgs);
        is(4, count($runner->dynamicActions));

        $className = 'test\Action\UpdatetestModel';

        is(true, isset($runner->dynamicActions[$className]));

        $generatedAction = $generator->generate('RecordActionTemplate',
            $className,
            $runner->dynamicActions[$className]['actionArgs']);

        $generatedAction->load();

        ok(class_exists($className));
    }

    public function testFildBased()
    {
        $generator = new ActionKit\ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());
        $template = $generator->loadTemplate('FileBasedActionTemplate');
        ok($template);

        $runner = new ActionKit\ActionRunner;
        $template->register($runner, 'FileBasedActionTemplate', array(
            'action_class' => 'User\\Action\\BulkUpdateUser',
            'template' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        is(1, count($runner->dynamicActions));

        $className = 'User\Action\BulkUpdateUser';

        is(true, isset($runner->dynamicActions[$className]));

        $generatedAction = $generator->generate('FileBasedActionTemplate',
            $className,
            $runner->dynamicActions[$className]['actionArgs']);

        $generatedAction->load();

        ok(class_exists($className));
    }

    public function testWithoutRegister()
    {
        $generator = new ActionKit\ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());

        $className = 'User\Action\BulkDeleteUser';

        $generatedAction = $generator->generate('FileBasedActionTemplate',
            $className,
            array(
                'template' => '@ActionKit/RecordAction.html.twig',
                'variables' => array(
                    'record_class' => 'User\\Model\\User',
                    'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
                )
            )
        );
        $generatedAction->load();
        ok( class_exists( $className ) );
    }

}

