<?php

namespace ActionKit\ValueType;

abstract class BaseType
{
    /**
     * Type option.
     * 
     * @var mixed
     */
    public $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

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
