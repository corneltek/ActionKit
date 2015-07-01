<?php
namespace ActionKit\Testing;
use PHPUnit_Framework_TestCase;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\GeneratedAction;
use ActionKit\Testing\ActionTestCase;

abstract class ActionTestCase extends PHPUnit_Framework_TestCase
{

    static $classCounter = 0;
    static $classPrefix = 'TestApp\Action\Foo';

    public function classNameProvider()
    {
        return [
            [static::$classPrefix . ++static::$classCounter . "Action"]
        ];
    }

    public function assertRequireGeneratedAction($className, GeneratedAction $generatedAction)
    {
        $this->assertNotNull($generatedAction);
        $generatedAction->load();
        $this->assertTrue(class_exists($className), "$className exists");
    }
}



