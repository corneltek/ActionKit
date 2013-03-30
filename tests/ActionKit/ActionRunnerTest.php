<?php

class ActionRunnerTest extends \LazyRecord\ModelTestCase
{

    public function getModels()
    {
        return array( 
            'User\Model\UserSchema',
            'Product\Model\ProductSchema',
        );
    }


    public function test()
    {
        $runner = ActionKit\ActionRunner::getInstance();
        ok($runner);
        $runner->registerAutoloader();
        $runner->registerCRUD('User','User',array('Create','Update','Delete'));

        $result = $runner->run('User::Action::CreateUser',array(
            'email' => 'foo@foo'
        ));
        ok($result);

        $json = $result->__toString();
        ok($json,'json output');
        $data = json_decode($json);
        ok($data->success);
        ok($data->data);
        ok($data->data->id);
    }
}

