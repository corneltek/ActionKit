<?php
namespace YamlKit;
use Spyc;

/* Integrate differnet yaml backend */

class YAML 
{
    static function detectBackend()
    {
        if( extension_loaded( 'yaml' ) ) {
            return 'yaml';
        }
        return 'Spyc';
    }

    static function load( $yaml )
    {
        if( extension_loaded( 'yaml' ) ) {
            return yaml_parse( $yaml );
        }
        return Spyc::YAMLLoad($yaml);
    }

    static function dump( $config )
    {
        if( extension_loaded( 'yaml' ) ) {
            return yaml_emit( $config );
        }
        return Spyc::YAMLDump( $config );
    }

    static function loadFile( $file )
    {
        if( extension_loaded( 'yaml' ) ) {
            return yaml_parse( file_get_contents( $file ) );
        }
        return Spyc::YAMLLoad($file);
    }

    static function dumpFile( $file , $config )
    {
        if( extension_loaded( 'yaml' ) ) {
            $yaml = yaml_emit( $config );
            return file_put_contents( $file , $yaml );
        }

        $yaml = Spyc::YAMLDump( $config );
        return file_put_contents( $file , $yaml );
    }
}
