<?php

namespace ActionKit\ValueType;

use DateTime;

class TimestampType extends BaseType
{
    /**
     * Accepts integer and strtotime string
     */
    public function test($value)
    {
        return strtotime($value) !== false || is_numeric($value) ? true : false;
    }


    /**
     * @return DateTime
     */
    public function parse($value)
    {
        if (is_numeric($value)) {
            return intval($value);
        } else {
            return strtotime($value);
        }
    }

    public function deflate($value)
    {
        return $value;
    }
}
