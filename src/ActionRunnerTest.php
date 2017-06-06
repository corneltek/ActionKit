<?php

namespace ActionKit;

use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\Testing\ActionTestAssertions;
use User\Model\UserSchema;

class CreateUserWithMoniker extends Action
{
    const moniker = 'create-user';

    public function run()
    {
        return $this->success('test', ["name" => "foo"]);
    }
}

/**
 * @group maghead
 */
class ActionRunnerTest extends \Maghead\Testing\ModelTestCase
{
    use ActionTestAssertions;

    public function models()
    {
        return [new UserSchema];
    }

    public function testMonikerAction()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $runner->run('ActionKit::CreateUserWithMoniker', []);
        $result = $runner->getResult('create-user');
        $this->assertNotNull($result);
        $this->assertEquals("foo", $result->data('name'));
    }

    public function testRegisterAction()
    {
        $container = new ServiceContainer;

        $generator = $container['generator'];
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate);
        $runner = new ActionRunner($container);
        $runner->registerAutoloader();
        $runner->registerAction('TwigActionTemplate', [
            'template' => '@ActionKit/RecordAction.html.twig',
            'action_class' => 'User\\Action\\BulkCreateUser',
            'variables' => [
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction',
            ]
        ]);

        $result = $runner->run('User::Action::BulkCreateUser', [
            'email' => 'foo@foo'
        ]);
        $this->assertNotNull($result);
    }

    public function testRegisterActionWithTwig()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate($container['twig_loader']));
        $runner = new ActionRunner($container);
        $runner->registerAutoloader();
        $runner->registerAction('TwigActionTemplate', array(
            'template' => '@ActionKit/RecordAction.html.twig',
            'action_class' => 'User\\Action\\BulkCreateUser',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));

        $result = $runner->run('User::Action::BulkCreateUser',array(
            'email' => 'foo@foo'
        ));
        $this->assertNotNull($result);
    }

    public function testRunAndJsonOutput()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate);

        $runner = new ActionRunner($container);
        $runner->registerAutoloader();
        $runner->registerAction('RecordActionTemplate', array(
            'namespace' => 'User',
            'model' => 'User',
            'types' => [
                [ 'prefix' => 'Create'],
                [ 'prefix' => 'Update'],
                [ 'prefix' => 'Delete'],
            ]
        ));

        $result = $runner->run('User::Action::CreateUser',[ 
            'email' => 'foo@foo'
        ]);
        $this->assertInstanceOf('ActionKit\\Result', $result);

        $json = $result->__toString();
        $this->assertNotNull($json, 'json output');
        $data = json_decode($json);
        $this->assertTrue($data->success);
        $this->assertNotNull($data->data);
        $this->assertNotNull($data->data->id);

        $results = $runner->getResults();
        $this->assertNotEmpty($results);

        $this->assertNotNull($runner->getResult('User::Action::CreateUser'));


        $runner->setResult('test', 'test message');
        $this->assertEquals(true, $runner->hasResult('test'));
        $runner->removeResult('test');
        $this->assertEquals(false, $runner->hasResult('test'));
    }

    public function testHandleWith()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);

        $stream = fopen('php://memory', 'rw');
        $result = $runner->handleWith($stream, array(
            'action' => 'User::Action::CreateUser',
            '__ajax_request' => 1,
            'email' => 'foo@foo'
        ));
        $this->assertEquals(true, $result);
        fseek($stream, 0);
        $output = stream_get_contents($stream);
        $this->assertStringEqualsFile('tests/fixture/handle_with_result.json', $output);
    }


    public function testRunnerArrayAccess()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);

        $runner['User::Action::CreateUser'] = new \ActionKit\Result;

        $this->assertTrue( isset($runner['User::Action::CreateUser']) );

        // Test Result getter
        $this->assertNotNull($runner['User::Action::CreateUser']);
    }


    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testHandleWithInvalidActionNameException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array(
            'action' => "_invalid"
        ));
    }

    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testHandleWithInvalidActionNameExceptionWithEmptyActionName()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array());  
        
    }

    /**
    *   @expectedException  ActionKit\Exception\ActionNotFoundException
    */
    public function testHandleWithActionNotFoundException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array(
            'action' => "User::Action::NotFoundAction",
        )); 
    }

    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testRunnerWithInvalidActionNameException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->run('!afers');
    }

    /**
    *   @expectedException  ActionKit\Exception\ActionNotFoundException
    */
    public function testRunnerWithActionNotFoundException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->run('Product::Action::Product');
    }

}
