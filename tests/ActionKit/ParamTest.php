<?php

class ParamTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $column = new ActionKit\Param('name');
        ok( $column );

        $column->required(1);
        ok($column->required);

        $column->default('John');
        is('John',$column->default);
    }
}

