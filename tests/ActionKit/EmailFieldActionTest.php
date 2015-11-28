<?php
use ActionKit\Action;


class EmailFieldTestAction extends Action
{
    public function schema()
    {
        $this->param('email')
            ->isa('Email');
    }
}



class EmailFieldActionTest extends PHPUnit_Framework_TestCase
{

    public function testEmailFieldAction()
    {
        $action = new EmailFieldTestAction([ 'email' => 'yoanlin93@gmail.com' ]);
        $ret = $action->invoke();
        $this->assertTrue($ret);
    }

}
