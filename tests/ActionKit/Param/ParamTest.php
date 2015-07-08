<?php
use ActionKit\Param\Param;
use ActionKit\Param\ImageParam;

class ParamTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $column = new Param('name');
        $column->required(1);
        ok($column->required);
        $column->default('John');
        is('John',$column->default);
    }

    public function testImageParam()
    {
        $image = new ImageParam('photo');
        ok($image->size(['width' => 100, 'height' => 200]));
        ok($image->autoResize(false));
        ok($image->autoResize(true));
    }
}

