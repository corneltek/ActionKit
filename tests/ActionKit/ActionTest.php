<?php
use ActionKit\Action;
use ActionKit\MixinAction;

class FakeMixin extends MixinAction {

    public function schema() {
        $this->param('handle')->type('string');
    }

}





class ImageParamTestAction extends Action {

    public function mixins() {
        return array( 
            new FakeMixin($this),
        );
    }

    public function schema() {
        $this->param('image','Image');
    }
}

class TestTakeFilterAction extends Action {

    public function schema() {
        $this->param('extra1');
        $this->param('extra2');
        $this->param('extra3');
        $this->param('only_this1');
        $this->param('only_this2');
        $this->takes('only_this1','only_this2');
    }

}

class LoginTestAction extends Action 
{

    public function schema() 
    {
        $this->param('username');
        $this->param('password');
        $this->filterOut(array('token'));
    }

    public function run()
    {
        // test filterOut
        if ($this->arg('token')) {
            return $this->error('token should be filter out.');
        }
        if ($this->arg('username') === 'admin' &&
            $this->arg('password') === 's3cr3t' ) {
                return $this->success('Login');
        }
        return $this->error('Login Error');
    }
}

class ActionTest extends PHPUnit_Framework_TestCase
{

    public function testGetParamsWithTakeFilter()
    {
        $take = new TestTakeFilterAction;
        $params = $take->getParams();
        ok($params,'get params');

        $keys = $take->takeFields;
        foreach( $keys as $k ) {
            ok( isset($params[$k]), "has key $k");
        }
        ok( ! isset($params['extra1']) );
        ok( ! isset($params['extra2']) );
        ok( ! isset($params['extra3']) );
    }

    public function testActionArrayOptions() {
        $action = new LoginTestAction([],[
            'current_user' => null,
        ]);
    }

    public function testActionContainerOptions() {
        $container = new \Pimple\Container;
        $action = new LoginTestAction([], $container);
    }

    public function testRender()
    {
        $login = new LoginTestAction;
        ok($login->render());
        ok($login->render('username'));
        ok($login->renderWidget('username'));
        ok($login->renderField('username'));
        ok($login->renderLabel('password'));
        ok($login->renderWidgets(['username', 'password']));
        ok($login->renderSubmitWidget());
        ok($login->renderButtonWidget());
        ok($login->renderSignatureWidget());
    }

    public function testGetParamsWithFilterOut() 
    {
        $login = new LoginTestAction;
        $params = $login->getParams(); // get params with param filter
        $this->assertNotEmpty($params);
        $this->assertCount(2, array_keys($params));
        ok( !isset($params['token']) , 'Should not include token param.' );
        ok( isset($params['username']) , 'Should have username param' );
        ok( isset($params['password']) , 'Should have password param' );
    }

    /*
    public function testImageParam() 
    {
        $action = new ImageParamTestAction(array(
            'image' => 1,
        ));
        ok($action);
    }
     */

    public function testFilterOut()
    {
        $action = new LoginTestAction(array(
            'username' => 'admin',
            'password' => 's3cr3t',
            'token' => 'blah',
        ));

        $success = $action->invoke();
        $result = $action->getResult();
        
        $this->assertTrue($success, $result->message);

        $result = $action->result;
        $this->assertNotNull($result,'Got Result');
        $this->assertEquals('Login', $result->message);
        $this->assertTrue($result->isSuccess());
    }

    public function testParams()
    {
        $login = new LoginTestAction;
        is($login->getName(), 'LoginTestAction');

        $result = $login->getWidgetsByNames(['username', 'password']);
        is(2, count($result));

        is('Foo', $login->arg('username', 'Foo'));

        $this->assertNotEmpty($login->params());
        $this->assertNotEmpty($login->params(true));

        $this->assertTrue($login->hasParam('username'));
        $this->assertNotNull($removed = $login->removeParam('username'));
        $this->assertFalse($login->hasParam('username'));
    }



    /**
    *   @expectedException  Exception
    */
    public function testWrongType()
    {
        $login = new LoginTestAction;
        $login->replaceParam('username', 'TestType');
    }
}

