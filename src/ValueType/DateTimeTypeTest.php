<?php

namespace ActionKit\ValueType;

class DateTimeTypeTest extends \PHPUnit\Framework\TestCase
{

    public function dateTimeDataProvider()
    {
        return [
            ['2015-01-01', true],
            [date('c'), true],
            ['foo', false],
            ['123', false],
        ];
    }


    /**
     * @dataProvider dateTimeDataProvider
     */
    public function testDateTimeTypeTest($input, $expected)
    {
        $type = new DateTimeType;
        $this->assertSame($expected, $type->test($input));
    }

    public function testDateTimeTypeParse()
    {
        $bool = new DateTimeType;
        $this->assertNotNull($bool->parse('2015-01-01'));
        $this->assertNotNull($bool->parse(date('c')));
    }


}

