<?php
namespace ActionKit\Testing;

use \PHPUnit\Framework\TestCase;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\Action;
use ActionKit\GeneratedAction;
use ActionKit\Testing\ActionTestCase;

abstract class ActionTestCase extends \PHPUnit\Framework\TestCase
{
    use ActionTestAssertions;

    public static $classCounter = 0;
    public static $classPrefix = 'TestApp\\Action\\Foo';

    public function classNameProvider()
    {
        return [
            [static::$classPrefix . ++static::$classCounter . "Action"]
        ];
    }
}
