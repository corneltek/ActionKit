<?php

namespace ActionKit\ValueType;
use DateTime;

class DateTimeType extends BaseType
{
    public function test($value)
    {
        $ret = date_parse($value);
        if ($ret === false || !empty($ret['errors'])) {
            return false;
        }
        return true;
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
