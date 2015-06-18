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

    public function testHandleWith()
    {
        $container = new ActionKit\ServiceContainer;
        $runner = new ActionKit\ActionRunner($container);
        
        //ob_start();
        $result = $runner->handleWith(STDOUT, array(
            'action' => 'User::Action::CreateUser',
            '__ajax_request' => 1,
            'email' => 'foo@foo'
        ));
        is(true, $result);
        // $output = ob_get_contents();
        // is('{"args":{"email":"foo@foo"},"success":true,"message":"User Record is created.","data":{"email":"foo@foo","id":1}}', $output);
        // ob_end_clean();
        // echo "output:".$output;
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
            'action' => "User::Action::NotFoundAction"
        )); 
    }

    /**
    *   @expectedException  ActionKit\Exception\UnableToWriteCacheException
    */
    public function testHandleWithUnableToWriteCacheException()
    {
        $container = new ActionKit\ServiceContainer;
        $container['cache_dir'] = '/InvalidCacheDir';
        $runner = new ActionKit\ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array(
            'action' => 'User::Action::CreateUser',
            'email' => 'foo@foo'
        )); 
    }
}

