<?php

namespace ActionKit\Storage;

use PHPUnit\Framework\TestCase;

class FilePathTest extends TestCase
{

    public function pathDataProvider()
    {
        return [
            ['upload/test_1200x300.jpg'],
        ];
    }

    /**
     * @dataProvider pathDataProvider
     */
    public function testParseAndToString($p)
    {
        $path = new FilePath($p);
        $this->assertEquals($p, $path->__toString());
    }
}

