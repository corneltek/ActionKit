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

    public function testArg()
    {
        $request = new ActionRequest([
            '__action' => 'MyApp::Action::CreateProduct',
            '__ajax_request' => true,
            'account' => 'user@gmail.com',
            'password' => md5('qwer1234'),
        ]);
        $this->assertEquals('user@gmail.com',$request->arg('account'));
    }

    public function testFiles()
    {
        $files = [
            'download' => [
                'name' => array(
                        'file1' => 'MyFile.txt',
                        'file2' => 'MyFile.jpg',
                ),
                'type' => array(
                        'file1' => 'text/plain',
                        'file2' => 'image/jpeg',
                    ),
                'tmp_name' => array (
                        'file1' => '/tmp/php/php1h4j1o',
                        'file2' => '/tmp/php/php6hst32',
                ),
                'error' => array(
                        'file1' => UPLOAD_ERR_OK,
                        'file2' => UPLOAD_ERR_OK,
                ),
                'size' => array(
                        'file1' => 123,
                        'file2' => 98174
                ),
            ],
        ];
        $request = new ActionRequest([
            '__action' => 'MyApp::Action::CreateProduct',
            '__ajax_request' => true,
            'account' => 'user@gmail.com',
            'password' => md5('qwer1234'),
        ], $files);
        $fileStash = $request->file('download');
        $this->assertNotNull($fileStash);
        $this->assertCount(2, $fileStash);
    }

    public function testInvalidActionName()
    {
        $request = new ActionRequest(array( 
            '__action' => 'bb_fjeijfe',
            '__ajax_request' => true,
            'account' => 'user@gmail.com',
            'password' => md5('qwer1234'),
        ));
        $this->assertEquals(1, $request->isInvalidActionName());
    }
}


