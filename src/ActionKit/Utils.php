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

    /**
     * Convert action signature into the actual full-qualified class name.
     *
     * This method replaces "::" charactors with "\" from action signature string.
     *
     * @param string $actionName
     */
    public static function toActionClass( $sig ) {
        // replace :: with '\'
        return str_replace( '::' , '\\' , $sig );
    }

    public static function createFileArrayFromPath($path)
    {
        $pathinfo = pathinfo($path);
        $file = array(
            'name' => $pathinfo['basename'],
            'tmp_name' => $path,
            'saved_path' => $path,
            'size' => filesize($path)
        );

        return $file;
    }
}



