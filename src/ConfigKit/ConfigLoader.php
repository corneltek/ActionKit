<?php
/**
 *  use ConfigKit\ConfigLoader;
 *  $compiled = ConfigLoader::compile('source_file.yml' , 'compiled_file.php');
 *  $config = ConfigLoader::load('source_file.yml', 'compiled_file.php');
 *  $config = ConfigLoader::load('source_file.yml');
 */
namespace ConfigKit;
use Exception;

class ConfigFileException extends Exception {  }

class ConfigLoader
{
    const FT_YML = 1;

    public static function compile($sourceFile,$compiledFile = null,$filetype = ConfigLoader::FT_YML ) { 
        if( ! $compiledFile ) {
            $p = strrpos($sourceFile,'.yml');
            if( $p === false ) {
                throw new ConfigFileException("$sourceFile file extension yml not found.");
            }
            $compiledFile = substr($sourceFile,0,$p) . '.php';
        }
        return $compiledFile;
    }
}
