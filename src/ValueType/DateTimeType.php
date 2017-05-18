<?php

namespace ActionKit\ValueType;

use DateTime;

class DateTimeType extends BaseType
{
    public function test($value)
    {
        // fixme: workaround
        if ($value instanceof DateTime) {
            return true;
        }
        $ret = strtotime($value);
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
