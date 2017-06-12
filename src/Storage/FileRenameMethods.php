<?php

namespace ActionKit\Storage;



class FileRenameMethods
{
    public static function filename_md5($filename, $tmpFile = null)
    {
        $md5 = $tmpFile ? md5($tmpFile) : md5($filename . time());
        $info = pathinfo($filename);
        return "{$info['dirname']}/{$md5}.{$info['extension']}";
    }

    public static function filename_append_md5($filename, $tmpFile = null)
    {
        $suffix = $tmpFile ? md5($tmpFile) : md5($filename . time());
        $pos = strrpos($filename , '.');
        if ($pos) {
            return
                substr( $filename , 0 , $pos )
                . $suffix
                . substr( $filename , $pos );
        }

        return $filename . $suffix;
    }

    public static function filename_increase($path)
    {
        if (! file_exists($path)) {
            return $path;
        }

        $pos = strrpos( $path , '.' );
        if ($pos !== false) {
            $filepath = substr($path, 0 , $pos);
            $extension = substr($path, $pos);
            $newfilepath = $filepath . $extension;
            $i = 1;
            while ( file_exists($newfilepath) ) {
                $newfilepath = $filepath . " (" . $i++ . ")" . $extension;
            }

            return $newfilepath;
        }

        return $path;
    }

    public static function filename_suffix( $filename , $suffix )
    {
        $pos = strrpos( $filename , '.' );
        if ($pos !== false) {
            return substr( $filename , 0 , $pos )
                . $suffix
                . substr( $filename , $pos );
        }

        return $filename . $suffix;
    }
    


}
