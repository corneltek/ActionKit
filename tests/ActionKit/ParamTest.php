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

    function testImage()
    {
        $image = new ActionKit\Param\Image('photo');
        ok($image);

        ok($image->size(['width' => 100, 'height' => 200]));

        ok($image->autoResize(false));
        ok($image->autoResize(true));

    }
}

