<?php

namespace ActionKit\ValueType;

class Ipv4Type extends BaseType
{
    public function test($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP , FILTER_FLAG_IPV4 | FILTER_NULL_ON_FAILURE) === null ? false : true;
    }

    public function parse($value)
    {
        return strval($value);
    }
}