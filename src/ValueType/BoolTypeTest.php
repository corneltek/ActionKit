<?php

namespace ActionKit\ValueType;

class BoolTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testBoolTypeValidator()
    {
        $bool = new BoolType;
        $this->assertTrue($bool->test('true'));
        $this->assertTrue($bool->test('false'));
        $this->assertTrue($bool->test('0'));
        $this->assertTrue($bool->test('1'));
        $this->assertFalse($bool->test('foo'));
        $this->assertFalse($bool->test('123'));
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

