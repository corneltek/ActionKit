<?php

namespace ActionKit\ValueType;

class IntType extends BaseType
{
    public function test($value)
    {
        return is_int($value) || filter_var($value, FILTER_VALIDATE_INT);
    }

    public function parse($value)
    {
        return intval($value);
    }
}
