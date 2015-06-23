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
        $generator = $container['generator'];
        $generator->registerTemplate(new ActionKit\ActionTemplate\FileActionTemplate);
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
        $generator = $container['generator'];
        $generator->registerTemplate(new ActionKit\ActionTemplate\RecordActionTemplate);
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

    public function testHandleWith()
    {
        $container = new ActionKit\ServiceContainer;
        $runner = new ActionKit\ActionRunner($container);

        $stream = fopen('php://memory', 'rw');
        $result = $runner->handleWith($stream, array(
            'action' => 'User::Action::CreateUser',
            '__ajax_request' => 1,
            'email' => 'foo@foo'
        ));
        is(true, $result);

        fseek($stream, 0);
        $output = stream_get_contents($stream);
        $expected_output = '{"args":{"email":"foo@foo"},"success":true,"message":"User Record is created.","data":{"email":"foo@foo","id":1}}';
        is($expected_output, $output);
    }

    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testHandleWithInvalidActionNameException()
    {
        $container = new ActionKit\ServiceContainer;
        $runner = new ActionKit\ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array(
            'action' => "_invalid"
        ));
    }

    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testHandleWithInvalidActionNameExceptionWithEmptyActionName()
    {
        $container = new ActionKit\ServiceContainer;
        $runner = new ActionKit\ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array());  
        
    }

    /**
    *   @expectedException  ActionKit\Exception\ActionNotFoundException
    */
    public function testHandleWithActionNotFoundException()
    {
        $container = new ActionKit\ServiceContainer;
        $runner = new ActionKit\ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array(
            'action' => "User::Action::NotFoundAction",
        )); 
    }
}
