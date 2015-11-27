<?php

namespace ActionKit\ValueType;

class BoolType extends BaseType
{
    public function test($value)
    {
        $var = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (is_bool($var)) {
            return true;
        } else {
            return false;
        }
    }

    public function parse($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
