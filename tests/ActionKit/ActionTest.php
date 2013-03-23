<?php
use ActionKit\Action;

class ImageParamTestAction extends Action {
    function schema() {
        $this->param('image','Image');
    }
}

class TestTakeFilterAction extends Action {

    function schema() {
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

    public function run() {
        // test filterOut
        if( $this->arg('token') ) {
            return $this->error('token should be filter out.');
        }

        if( $this->arg('username') == 'admin' &&
            $this->arg('password') == 's3cr3t' ) {
                return $this->success('Login');
        }
        return $this->error('Error');
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

    public function testGetParamsWithFilterOut() 
    {
        $login = new LoginTestAction;
        ok($login);

        $params = $login->getParams(); // get params with param filter
        ok( $params );

        count_ok(2, array_keys($params));
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
        ok($action,'Got action');

        $success = $action->invoke();
        ok($success, $action->result->message);

        $result = $action->result;
        ok($result,'Got Result');

        is('Login', $result->message);
        ok( $result->isSuccess() );
    }

    function testRecordAction() 
    {
        $createUser = new User\Action\CreateUser(array(
            'account' => '1234',
            'password1' => '123456',
            'password2' => '123456',
            'role' => 'user',
        ));
        ok($createUser);

        $ret = $createUser->invoke();
        ok($ret);

        $result = $createUser->getResult();
        ok($result);
        is('success',$result->type);
        ok($createUser->record->delete()->success);
    }

    function testChangePasswordActionView() {
        $user = new User\Model\User;
        $ret = $user->create(array(
            'account' => 'user111',
            'password' => 'asdf',
        ));
        ok($ret->success,$ret);

        $action = new User\Action\ChangePassword(array(),$user);
        ok($action);

        $view = $action->asView('AdminUI\\Action\\View\\StackView');
        ok($view,'Got View');
        isa_ok('AdminUI\\Action\\View\\StackView',$view);

        $html = $view->render();
        ok($html);

        $user->delete();
        // TODO: more detailed testing
    }
}

