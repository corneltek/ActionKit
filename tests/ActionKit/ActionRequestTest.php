<?php
use ActionKit\ActionRequest;

class ActionRequestTest extends PHPUnit_Framework_TestCase
{
    public function testActionRequest()
    {
        $request = new ActionRequest(array( 
            '__action' => 'MyApp::Action::CreateUser',
            '__ajax_request' => true,
            'account' => 'user@gmail.com',
            'password' => md5('qwer1234'),
        ));
        $this->assertTrue($request->isAjax());
        $this->assertEquals('MyApp::Action::CreateUser',$request->getActionName());
        $this->assertSame([ 
            'account' => 'user@gmail.com',
            'password' => '5d93ceb70e2bf5daa84ec3d0cd2c731a',
        ], $request->getArguments());
    }

    public function testFullQualifiedName()
    {
        $request = new ActionRequest(array( 
            '__action' => 'MyApp::Action::CreateUser',
            '__ajax_request' => true,
            'account' => 'user@gmail.com',
            'password' => md5('qwer1234'),
        ));
        $this->assertTrue($request->isFullQualifiedName());
    }

    public function testNonFullQualifiedName()
    {
        $request = new ActionRequest(array( 
            '__action' => 'CreateUser',
            '__ajax_request' => true,
            'account' => 'user@gmail.com',
            'password' => md5('qwer1234'),
        ));
        $this->assertFalse($request->isFullQualifiedName());
    }
}


