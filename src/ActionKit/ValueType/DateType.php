<?php

namespace ActionKit\ValueType;

class DateType extends BaseType
{
    public function test($value)
    {
        return date_parse($value) !== false ? true : false;
    }

    public function parse($value)
    {
        return date_parse($value);
    }
}
