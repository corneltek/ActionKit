<?php

namespace ActionKit\ValueType;

use PHPUnit\Framework\TestCase;

class JsonTypeTest extends TestCase
{
    public function jsonDataProvider()
    {
        return [
            /* input, expected, test pass */
            ['{ "foo": "bar" }', (object) [ "foo" => "bar" ], true],
            ["", null, true],
        ];
    }

    /**
     * @dataProvider jsonDataProvider
     */
    public function testJsonType($input, $expected, $success)
    {
        $type = new JsonType;
        $this->assertSame($success, $type->test($input));

        if ($expected instanceof \Object) {
            $this->assertSame($expected, $type->parse($input));
        } else {
            $this->assertEquals($expected, $type->parse($input));
        }
    }
}



