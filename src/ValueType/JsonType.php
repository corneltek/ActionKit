<?php

namespace ActionKit\ValueType;

use Exception;

class JsonType extends BaseType
{
    public function test($value)
    {
        if ($value === "") {
            return true;
        }

        try {

            // NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit.
            $ret = json_decode($value);
            return $ret !== null;

        } catch (Exception $e) {

            return false;

        }

        return false;
    }

    public function parse($value)
    {
        if ($value === "") {
            return null;
        }

        return json_decode($value);
    }

    public function deflate($value)
    {
        return var_export($value, true);
    }
}
