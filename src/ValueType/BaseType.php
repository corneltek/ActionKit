<?php

namespace ActionKit\ValueType;

abstract class BaseType
{
    /**
     * Test a value to see if it can be parsed into the type.
     *
     * @param mixed $value
     */
    abstract public function test($value);

    /**
     * Parse a string value into it's type value.
     *
     * @param mixed $value
     */
    abstract public function parse($value);

    abstract public function deflate($value);

}
