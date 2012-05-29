<?php
namespace ActionKit;

/**
 * Action Generator
 *
 *    $generator = new ActionGenerator(array( 
 *          'cache' => true,                 // this enables apc cache.
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

    public $cache;

    public $cacheDir;

    function __construct( $options = array() ) 
    {
        $this->cache = isset($options['cache']) && extension_loaded('apc');
    }

    function generateClassCode( $modelClass , $type ) {
        if( $this->cache && $code = apc_fetch( 'action:' . $modelClass . ':' . $type) ) {
            return $code;
        }

        $p = strpos( $modelClass , '\\' );
        $bp = strrpos( $modelClass , '\\' );
        $ns = substr($modelClass,0,$p);
        $modelName = substr($modelClass, $bp + 1 );
        $code = $this->generateClassCodeWithNamespace( $ns , $modelName, $type );

        if( $this->cache ) {
            apc_store( 'action:' . $modelClass . ':' . $type , $code );
        }
        return $code;
    }

    function generateClassCodeWithNamespace( $ns , $modelName , $type )
    {
        $recordClass  = '\\' . $ns . '\Model\\' . $modelName;
        $actionClass  = $type . $modelName;
        $baseAction   = $type . 'RecordAction';
        $code =<<<CODE
namespace $ns\\Action {
    use ActionKit\\RecordAction\\$baseAction;
    class $actionClass extends $baseAction
    {
        public \$recordClass = '$recordClass';
    }
}
namespace { return 1; }
CODE;
        return $code;
    }


}


