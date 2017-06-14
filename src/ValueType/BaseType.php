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

    public static $typeClasses = [];

    public static function create($isa)
    {
        $isa = ucfirst($isa);
        if (!isset(self::$typeClasses[$isa])) {
            $class = "ActionKit\\ValueType\\{$isa}Type";
            return self::$typeClasses[$isa] = new $class;
        }
        return self::$typeClasses[$isa];
    }
}
