<?php

namespace ActionKit\ValueType;

/*
 * Returns TRUE for "1", "true", "on" and "yes". Returns FALSE otherwise.
 *
 * If FILTER_NULL_ON_FAILURE is set, FALSE is returned only for "0", "false",
 * "off", "no", and "", and NULL is returned for all non-boolean values.
 */
class BoolType extends BaseType
{
    public function test($value)
    {
        if ($value === "") {
            return true;
        }

        $var = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (is_bool($var)) {
            return true;
        } else {
            return false;
        }
    }

    public function parse($value)
    {
        if ($value === "") {
            return null;
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    public function deflate($value)
    {
        return var_export($value, true);
    }
}
