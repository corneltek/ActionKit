<?php

namespace ActionKit\Param;

use DateTime;

class ParamTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $p = new Param('name');
        $p->required(1);
        $this->assertNotNull($p->required);

    }

    public function testDefaultValueByScalar()
    {
        $p = new Param('name');
        $p->default('John');
        $this->assertEquals('John', $p->getDefaultValue());
    }

    public function testDefaultValueByClosure()
    {
        $p = new Param('created_at');
        $p->default(function() {
            return new DateTime;
        });
        $this->assertInstanceOf(DateTime::class, $p->getDefaultValue());
    }

    public function testValidValuesByArray()
    {
        $p = new Param('type');
        $p->validValues([
            'user',
            'admin',
            'guest',
        ]);
        $validValues = $p->getValidValues();
        $this->assertEquals([
            'user',
            'admin',
            'guest',
        ], $validValues);
    }


    public function testValidValuesByClosure()
    {
        $p = new Param('type');
        $p->validValues(function() {
            return [ 'user', 'admin', 'guest' ];
        });
        $this->assertTrue($p->isValidValue('user'));
        $this->assertFalse($p->isValidValue('foo'));
    }
}

