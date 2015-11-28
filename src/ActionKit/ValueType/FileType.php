<?php

namespace ActionKit\ValueType;

use SplFileInfo;

class FileType extends BaseType
{
    public function test($value)
    {
        return is_file($value);
    }

    public function parse($value)
    {
        return new SplFileInfo($value);
    }

    public function deflate($value)
    {
        return $value->getPathname();
    }
}
