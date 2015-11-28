<?php

namespace ActionKit\ValueType;
use DateTime;

class DateTimeType extends BaseType
{
    public function test($value)
    {
        return date_parse($value) !== false ? true : false;
    }


    /**
     * @return DateTime
     */
    public function parse($value)
    {
        return new DateTime($value);
    }

    public function deflate($value)
    {
        return $value->format(DateTime::ATOM);
    }
}
