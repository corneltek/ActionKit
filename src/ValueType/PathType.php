<?php

namespace ActionKit\ValueType;

use SplFileInfo;

class PathType extends BaseType
{
    public function test($value)
    {
        return file_exists($value);
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
