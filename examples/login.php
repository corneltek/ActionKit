<?php
/*
 * php -S localhost:3333 -t example
 */
require '../vendor/autoload.php';
session_start();
use ActionKit\Action;
use ActionKit\ActionRunner;
use ActionKit\ServiceContainer;

class MyLoginAction extends Action {

    public function schema() {
        $this->param('email')->renderAs('TextInput');
        $this->param('password')->renderAs('PasswordInput');
    }

    public function run() {

        if ( $this->arg('email') == 'test@test.com' &&
            $this->arg('password') == 'test') {
            return $this->success('登入成功');
        } else {
            if( $this->arg('email') != 'test@test.com') {
                return $this->error('無此帳號');
            } else if($this->arg('password') != 'test') {
                return $this->error('密碼錯誤');
            }
        }
    }
}

$container = new ActionKit\ServiceContainer;
$runner = new ActionKit\ActionRunner($container);

// you can also run action directly
// $result = $runner->run('MyLoginAction',array( 'email' => '...', 'password' => '...' ));

if (isset($_POST['action'])) {
    $sig = $_POST['action'];
    unset($_POST['action']);
    $result = $runner->run($sig, $_POST);
    //var_dump($result);
    echo $result->getMessage();
} else {
    $action = new MyLoginAction;
    echo $action->asView()->render();  // implies view class ActionKit\View\StackView
}
