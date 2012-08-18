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
    public static function _compile_file($sourceFile,$compiledFile) {
        $config = yaml_parse($sourceFile);
        if( file_put_contents( $compiledFile , '<?php return ' . var_export($config,true) . ';' ) === false ) {
            throw new ConfigFileException("Can not write config file.");
        }
        return $config;
    }

    public static function compile($sourceFile,$compiledFile = null) { 
        if( ! $compiledFile ) {
            $p = strrpos($sourceFile,'.yml');
            if( $p === false ) {
                throw new ConfigFileException("$sourceFile file extension yml not found.");
            }
            $compiledFile = substr($sourceFile,0,$p) . '.php';
        }
        if( ! file_exists($compiledFile) 
            || (file_exists($compiledFile) && filemtime($sourceFile) > filemtime($compiledFile))
            ) {
            self::_compile_file($sourceFile,$compiledFile);
        }
        return $compiledFile;
    }
}
