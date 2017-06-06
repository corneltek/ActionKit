<?php

namespace ActionKit\ValueType;

class IntTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testIntType()
    {
        $bool = new IntType;
        $this->assertTrue($bool->test(1));
        $this->assertTrue($bool->test(100));
        $this->assertTrue($bool->test(-10));
        $this->assertTrue($bool->test('123'));
        $this->assertTrue($bool->test('-10'));
        $this->assertFalse($bool->test('foo'));
    }
}

