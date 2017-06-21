<?php

namespace ActionKit\Param;

use ActionKit\Action;

class ImageParamTest extends \PHPUnit\Framework\TestCase
{
    public function testImageParam()
    {
        $image = new ImageParam('photo', new Action);
        $this->assertNotNull($image->size(['width' => 100, 'height' => 200]));
        $this->assertNotNull($image->autoResize(false));
        $this->assertNotNull($image->autoResize(true));
    }


}

