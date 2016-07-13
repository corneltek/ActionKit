<?php

namespace ActionKit\ValueType;

class IntType extends BaseType
{
    public function test($value)
    {
        return is_int($value) || filter_var($value, FILTER_VALIDATE_INT) || is_numeric($value);
    }

    public function parse($value)
    {
        return intval($value);
    }

    public function deflate($value)
    {
        return $value;
    }
}
