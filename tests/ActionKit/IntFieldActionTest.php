<?php
use ActionKit\Action;


class IntFieldTestAction extends Action
{

    public function schema()
    {
        $this->param('cnt')
            ->isa('Int');
    }

}


class IntFieldActionTest extends \PHPUnit\Framework\TestCase
{

    public function testIntFieldAction()
    {
        $action = new IntFieldTestAction([ 'cnt' => 10 ]);
        $ret = $action->invoke();
        $this->assertTrue($ret);
    }

}
