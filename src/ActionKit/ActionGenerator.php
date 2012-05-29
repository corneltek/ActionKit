<?php
namespace ActionKit;

/**
 * Action Generator
 *
 *    $generator = new ActionGenerator(array( 
 *          'cache_dir' => 'phifty/cache', 
 *          'template_dirs' => array( 'Resource/Templates' )
 *    ));
 *    $classFile = $generator->generate( 'Plugin\Action\TargetClassName', 'CreateRecordAction.template' , array( ));
 *    require $classFile;
 *
 *
 * Depends on Twig template engine
 *
 */
class ActionGenerator
{
    public $cacheDir;

    function __construct( $options ) {
        if( isset($options['cache_dir']) ) {
            $this->cacheDir = $options['cache_dir'];
        }
    }

    function generate($className, $templateName, $arguments ) {

    }
}


