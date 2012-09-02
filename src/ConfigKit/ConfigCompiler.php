<?php
/**
 *  use ConfigKit\ConfigCompiler;
 *  $compiled = ConfigCompiler::compile('source_file.yml' , 'compiled_file.php');
 *  $config = ConfigCompiler::load('source_file.yml', 'compiled_file.php');
 *  $config = ConfigCompiler::load('source_file.yml');
 */
namespace ConfigKit;
use Exception;

class ConfigFileException extends Exception {  }

class ConfigCompiler
{
    public static function _compile_file($sourceFile,$compiledFile) {
        $config = yaml_parse( file_get_contents( $sourceFile ) );
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

    public static function load($sourceFile,$compiledFile = null) {
        $file = self::compile($sourceFile,$compiledFile);
        return require $file;
    }

    public static function unlink($sourceFile,$compiledFile = null) {
        $file = self::compile($sourceFile,$compiledFile);
        return unlink($file);
    }
}
