<?php

namespace ActionKit\ValueType;

class IntTypeTest extends \PHPUnit\Framework\TestCase
{

    public function intDataProvider()
    {
        return [
            [1, true],
            [100, true],
            [-100, true],

            ["", NULL],

            ['123', true],
            ['10', true],
            ['-10', true],
            ['foo', false],
        ];
    }


    /**
     * @dataProvider intDataProvider
     */
    public function testIntType($input, $expect)
    {
        $bool = new IntType;
        $this->assertEquals($expect, $bool->test($input));
    }
}

