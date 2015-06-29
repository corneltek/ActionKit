<?php
use ActionKit\Action;

class ResultTest extends PHPUnit_Framework_TestCase 
{
    public function testResult()
    {
        $login = new LoginTestAction;
        $login->arg('username', 'Foo');

        $username = $login->getParam('username');
        $username->required = true;
        $result = $login->runValidate();
        $result = $login->getResult();
        ok($result);

        is('error', $result->type);
        is(false, $result->isSuccess());
        is(true, $result->isError());
        ok($result->message);
    }
}