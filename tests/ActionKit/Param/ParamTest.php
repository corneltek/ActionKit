<?php
use ActionKit\Param\Param;
use ActionKit\Param\ImageParam;

class ParamTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $column = new Param('name');
        $column->required(1);
        $this->assertNotNull($column->required);
        $column->default('John');
        $this->assertEquals('John',$column->default);
    }

    public function testImageParam()
    {
        $image = new ImageParam('photo');
        $this->assertNotNull($image->size(['width' => 100, 'height' => 200]));
        $this->assertNotNull($image->autoResize(false));
        $this->assertNotNull($image->autoResize(true));
    }
}

