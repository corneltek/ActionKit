<?php

namespace ActionKit\ValueType;

class BoolTypeTest extends \PHPUnit\Framework\TestCase
{
    public function boolDataProvider()
    {
        return [
            /* input, expected, test pass */
            ["true", true, true],
            ["True", true, true],
            ["on", true, true],
            ["false", false, true],
            ["False", false, true],
            ["off", false, true],
            ["0", false, true],
            ["1", true, true],
            ["", null, true],

            // for parse failed value, it should always return NULL
            ["foo", null, false],
            ["123", null, false],
        ];
    }

    /**
     * @dataProvider boolDataProvider
     */
    public function testBoolTypeValidator($input, $expected, $success)
    {
        $bool = new BoolType;
        $this->assertSame($success, $bool->test($input));
        $this->assertSame($expected, $bool->parse($input));
    }
}

