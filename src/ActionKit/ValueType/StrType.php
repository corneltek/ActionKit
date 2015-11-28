<?php

namespace ActionKit\ValueType;

class StrType extends BaseType
{
    public function test($value)
    {
        return is_string($value);
    }

    public function parse($value)
    {
        return strval($value);
    }

    public function deflate($value)
    {
        return $value;
    }
}
