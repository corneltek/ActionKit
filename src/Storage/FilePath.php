<?php

namespace ActionKit\Storage;

class FilePath {

    public $dirname;

    public $filename;

    public $extension;

    public function __construct($filepath)
    {
        $info = pathinfo($filepath);
        $this->dirname = $info['dirname'];
        $this->extension = $info['extension'];
        $this->filename = $info['filename'];
    }

    public function __toString()
    {
        return "{$this->dirname}/{$this->filename}.{$this->extension}";
    }
}
