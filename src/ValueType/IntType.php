<?php

namespace ActionKit\ValueType;

/**
 * To support nullable, empty string will be treated as NULL
 */
class IntType extends BaseType
{
    public function test($value)
    {
        return $value === "" 
            || is_int($value) 
            || filter_var($value, FILTER_VALIDATE_INT) 
            || is_numeric($value);
    }

    public function parse($value)
    {
        if ($value === "") {
            return null;
        }
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    public function deflate($value)
    {
        return $value;
    }
}
