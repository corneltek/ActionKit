<?php

namespace ActionKit\ValueType;

abstract class BaseType
{
    /**
     * Test a value to see if it fit the type.
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
}
