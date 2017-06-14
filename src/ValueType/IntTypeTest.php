<?php

namespace ActionKit\ValueType;

class IntTypeTest extends \PHPUnit\Framework\TestCase
{

    public function intDataProvider()
    {
        return [
            [1    , 1     , true] , 
            [100  , 100   , true] , 
            [-100 , -100  , true] , 

            ["",   false   , false],

            ['123', 123, true],
            ['10', 10, true],
            ['-10', -10, true],
            ['foo', false, false],
        ];
    }


    /**
     * @dataProvider intDataProvider
     */
    public function testIntTypeTest($input, $expect, $success)
    {
        $bool = new IntType;
        $this->assertEquals($success, $bool->test($input));
        $this->assertSame($expect, $bool->parse($input));
    }
}

