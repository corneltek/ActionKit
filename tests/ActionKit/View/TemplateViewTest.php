<?php

class TemplateViewTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $actionClass = ActionKit\CRUD::generate('User\Model\User','Create');
        $action = new $actionClass;
        ok($action);
        $view = new FooTemplateView($action);
        ok($view);
        ok($view->render());
    }
}

