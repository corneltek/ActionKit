<?php
namespace ActionKit;
use Twig_Loader_Filesystem;
use ReflectionClass;
use ActionKit\ColumnConvert;

use Pekkis\MimeTypes\MimeTypes;

class Utils
{
    public static function validateActionName($actionName) {
        return ! preg_match( '/[^A-Za-z0-9:]/i' , $actionName  );
    }

    /**
    * @param string $path     file path.
    * @param string $basepath basepath for checking file existence
    */
    public static function filename_increase_suffix_number($path, $basepath = './')
    {
        if ( ! file_exists($basepath . $path) ) {
            return $path;
        }
        $pos = strrpos( $path , '.' );
        if ($pos !== false) {
            $filepath = substr($path, 0 , $pos);
            $extension = substr($path, $pos);
            $newfilepath = $filepath . $extension;
            $i = 1;
            while ( file_exists($basepath . $newfilepath) ) {
                $newfilepath = $filepath . "_" . $i++ . $extension;
            }
            return $newfilepath;
        }
        return $path;
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

    public static function createFileArrayFromPath($path, $uploadedFile = null)
    {
        $filetype = $uploadedFile ? $uploadedFile->getType() : null;
        if (!$filetype) {
            $mt = new MimeTypes;
            $filetype = $mt->resolveMimeType($path);
        }
        $pathinfo = pathinfo($path);
        $file = array(
            'name' => $pathinfo['basename'],
            'tmp_name' => $path,
            'type' => $filetype,
            'saved_path' => $path,
            'size' => filesize($path)
        );
        return $file;
    }
}



