<?php

namespace ActionKit\Storage;

use PHPUnit\Framework\TestCase;

class FilePathTest extends TestCase
{

    public function pathDataProvider()
    {
        return [
            ['upload/test_1200x300.jpg'],
            ['upload/test_中文.png'],
            ['upload/test.bak.png'],
            ['upload/to/path/test.bak.png'],
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

