<?php

namespace ActionKit\ValueType;

class EmailType extends BaseType
{
    public function test($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) ? true : false;
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
