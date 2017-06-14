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

    public function falseValueProvider()
    {
        return [
            ['FALSE'],
            ['False'],
            ['false'],
            ['0'],
        ];
    }

    public function trueValueProvider()
    {
        return [
            ['TRUE'],
            ['True'],
            ['true'],
            ['1'],
        ];
    }

    /**
     * @dataProvider trueValueProvider
     */
    public function testTrueBoolTypeParse($str)
    {
        $bool = new BoolType;
        $this->assertTrue($bool->parse($str));
    }

    /**
     * @dataProvider falseValueProvider
     */
    public function testFalseBoolTypeParse($str)
    {
        $bool = new BoolType;
        $this->assertFalse($bool->parse($str));
    }
}

