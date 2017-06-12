<?php

namespace ActionKit\Storage;

/**
 * FilePath is a plain old object for presenting the file path for reassembling
 * the file path with different filename, extension ... etc
 */
class FilePath {

    public $dirname;

    public $filename;

    public $extension;

    public $basename;

    public function __construct($filepath)
    {
        $info = pathinfo($filepath);
        $this->dirname = $info['dirname'];
        $this->extension = $info['extension'];
        $this->filename = $info['filename'];
        $this->basename = $info['basename'];
    }

    /**
     * Append a suffix to the current filename.
     */
    public function appendFilenameSuffix($suffix)
    {
        $this->filename = "{$this->filename}{$suffix}";
    }

    public function exists()
    {
        $p = $this->__toString();
        return file_exists($p);
    }

    /**
     * The rename method returns a new FilePath to copy the instance.
     *
     * @return FilePath
     */
    public function renameAs($newfilename)
    {
        $newp = clone $this;
        $newp->filename = $newfilename;
        return $newp;
    }

    public function __toString()
    {
        return "{$this->dirname}/{$this->filename}.{$this->extension}";
    }
}
