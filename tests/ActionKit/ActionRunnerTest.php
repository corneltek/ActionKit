<?php

class ActionRunnerTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $runner = ActionKit\ActionRunner::getInstance();
        ok($runner);

        $runner->registerAutoloader();

        if( class_exists('User\User',true) ) {
            $create = new User\Action\CreateUser;
            ok($create);
        }
    }
}

