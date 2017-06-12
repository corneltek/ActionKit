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

    public function testFilenameRename()
    {
        $p = new FilePath('upload/test_中文.jpg');
        $p2 = $p->renameAs('foo');
        $this->assertEquals('upload/foo.jpg', $p2->__toString());
    }

    public function testUniqid()
    {
        $p = new FilePath('upload/foo.jpg');
        $p->appendFilenameUniqid();
        $this->assertRegExp('!upload/foo_\w+.jpg!', $p->__toString());
    }

    public function testStrip()
    {
        $p = new FilePath('upload/test_(1200x300)_中文.jpg');
        $p->strip();
        $this->assertEquals('upload/test_1200x300.jpg', $p->__toString());
    }


    public function testSuffix()
    {
        $p = new FilePath('upload/test.jpg');
        $p->appendFilenameSuffix('_foo');
        $this->assertEquals('upload/test_foo.jpg', $p->__toString());
    }

    public function testExists()
    {
        $p = new FilePath('src/Action.php');
        $this->assertTrue($p->exists());
    }
}

