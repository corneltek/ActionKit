<?php
use ActionKit\ValueType\DateTimeType;

class DateTimeTypeTest extends PHPUnit_Framework_TestCase
{
    public function testDateTimeTypeTest()
    {
        $bool = new DateTimeType;
        $this->assertTrue($bool->test('2015-01-01'));
        $this->assertTrue($bool->test(date('c')));
        $this->assertFalse($bool->test('foo'));
        $this->assertFalse($bool->test('123'));
    }

    public function testDateTimeTypeParse()
    {
        $bool = new DateTimeType;
        $this->assertNotNull($bool->parse('2015-01-01'));
        $this->assertNotNull($bool->parse(date('c')));
    }


}

