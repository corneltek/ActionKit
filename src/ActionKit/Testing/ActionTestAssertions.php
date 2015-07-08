<?php
namespace ActionKit\Testing;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\Action;
use ActionKit\GeneratedAction;
use ActionKit\Testing\ActionTestCase;

trait ActionTestAssertions
{
    public function assertRequireGeneratedAction($className, GeneratedAction $generatedAction)
    {
        $this->assertNotNull($generatedAction);
        $generatedAction->load();
        $this->assertTrue(class_exists($className), "$className exists");
    }

    public function assertActionInvokeSuccess(Action $action)
    {
        $ret = $action->invoke();
        $result = $action->getResult();
        $this->assertTrue($ret, $result->message);
        $this->assertEquals('success',$result->type, $result->message);
        return $result;
    }
    
}




