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

    static $classCounter = 0;
    static $classPrefix = 'TestApp\\Action\\Foo';

    public function classNameProvider()
    {
        return [
            [static::$classPrefix . ++static::$classCounter . "Action"]
        ];
    }

    public static function assertFileEquals($expectedFile, $actualFile, $message = '', $canonicalize = false, $ignoreCase = false)
    {
        if (!file_exists($expectedFile)) {
            copy($actualFile, $expectedFile);
            echo PHP_EOL, "Added expected file: ", $expectedFile, PHP_EOL;
            echo "=========================================", PHP_EOL;
            echo file_get_contents($expectedFile), PHP_EOL;
            echo "=========================================", PHP_EOL;
        }
        parent::assertFileEquals($expectedFile, $actualFile, $message, $canonicalize, $ignoreCase);
    }
}



