<?php

namespace ActionKit\Storage\FileRename;

use ActionKit\Action;
use ActionKit\Storage\FileRenameMethods;
use Universal\Http\UploadedFile;
use ActionKit\Param\Param;

class Md5Rename
{
    public function __invoke($newFile, $tmpFile, UploadedFile $uploadedFile = null)
    {
        return FileRenameMethods::md5ize($newFile, $tmpFile, $uploadedFile);
    }
}
