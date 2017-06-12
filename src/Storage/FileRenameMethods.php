<?php

namespace ActionKit\Storage;

use Universal\Http\UploadedFile;
use ActionKit\Action;
use RuntimeException;

class FileRenameMethods
{
    public static function md5ize($file, $tmpFile, UploadedFile $uf = null, Action $a = null)
    {
        if (!file_exists($tmpFile)) {
            throw new RuntimeException("filename to md5 require the tmp file to exist.");
        }
        $md5 = md5_file($tmpFile);
        $p = new FilePath($file);
        $p2 = $p->renameAs($md5);
        return $p2->__toString();
    }
}
