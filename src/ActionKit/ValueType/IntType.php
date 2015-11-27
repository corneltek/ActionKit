<?php
namespace ActionKit\ValueType;

class IntType extends BaseType
{
    public function test($value)
    {
        return is_int($value);
    }

    public function parse($value)
    {
        return intval($value);
    }
}



