<?php

class ActionRunnerTest extends \LazyRecord\Testing\ModelTestCase
{

    public function getModels()
    {
        return array( 
            'User\Model\UserSchema',
            'Product\Model\ProductSchema',
        );
    }

    public function testRegisterAction()
    {
        $container = new ActionKit\ServiceContainer;
        $runner = new ActionKit\ActionRunner($container);
        $runner->registerAutoloader();
        $runner->registerAction(
            'User\\Action\\BulkCreateUser',
            '@ActionKit/RecordAction.html.twig',
            array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        );

        $result = $runner->run('User::Action::BulkCreateUser',array(
            'email' => 'foo@foo'
        ));
        ok($result);
    }

    public function test()
    {
        $container = new ActionKit\ServiceContainer;
        $runner = new ActionKit\ActionRunner($container);
        ok($runner);
        $runner->registerAutoloader();
        $runner->registerRecordAction('User','User',array('Create','Update','Delete'));

        $result = $runner->run('User::Action::CreateUser',[ 
            'email' => 'foo@foo'
        ]);
        ok($result);


        $json = $result->__toString();
        ok($json,'json output');
        $data = json_decode($json);
        ok($data->success);
        ok($data->data);
        ok($data->data->id);
    }
}

