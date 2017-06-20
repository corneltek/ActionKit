<?php

namespace ActionKit\Param;

class ImageParamTest extends \PHPUnit\Framework\TestCase
{
    public function testImageParam()
    {
        $image = new ImageParam('photo');
        $this->assertNotNull($image->size(['width' => 100, 'height' => 200]));
        $this->assertNotNull($image->autoResize(false));
        $this->assertNotNull($image->autoResize(true));
    }


}

