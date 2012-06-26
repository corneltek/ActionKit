<?php
namespace I18N;
use CLIFramework\Logger;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use I18N\Exceptions\EncodingException;

class Scanner
{
    public $logger;

    public $paths = array();

    public $plugins = array();

    public $messages = array();

    function __construct()
    {
        $this->logger = new Logger;
    }

    public function addPlugin($plugin) {
        $this->plugins[] = $plugin;
    }

    public function addPath($path) {
        $this->paths[] = $path;
    }

    public function scan() {
        foreach( $this->paths as $path ) {
            if( is_dir($path) ) {
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path),
                                                        RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($iterator as $path) {
                    if ($path->isDir()) {
                        // rmdir($path->__toString());
                    } else {
                        // unlink($path->__toString());
                    }
                }
            }
        }
    }

    public function addMessage($msgId,$msgStr) {
        $this->messages[ $msgId ] = $msgStr;
    }

    public function scanFile($file) {
        $content = file_get_contents($file);
        // detect encoding

        $encoding = mb_detect_encoding($content);
        if( $encoding === false ) {
            throw new EncodingException;
        }

        // convert to utf8
        $utf8Content = mb_convert_encoding( $content, 'UTF-8' , $encoding );

        foreach( $this->plugins as $plugin ) {
            $plugin->scan( $utf8Content, $this );
        }
    }
}




