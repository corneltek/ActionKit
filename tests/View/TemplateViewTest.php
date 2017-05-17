<?php

class TemplateViewTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $actionClass = ActionKit\RecordAction\BaseRecordAction::createCRUDClass('User\Model\User','Create');
        $action = new $actionClass;
        $this->assertNotNull($action);

        $view = new FooTemplateView($action);
        $this->assertNotNull($view);
        $this->assertNotNull($view->render());
    }
}

