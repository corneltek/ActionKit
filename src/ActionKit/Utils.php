<?php
namespace ActionKit;
use Twig_Loader_Filesystem;
use ReflectionClass;
use ActionKit\ColumnConvert;

class Utils
{
    public static function validateActionName($actionName) {
        return ! preg_match( '/[^A-Za-z0-9:]/i' , $actionName  );
    }
}



