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

    function generateClassCode( $modelClass , $type ) 
    {
        $modelClass = ltrim($modelClass, '\\');
        $p          = strpos($modelClass, '\\');
        $bp         = strrpos($modelClass, '\\');
        $ns         = substr($modelClass, 0, $p);
        $modelName  = substr($modelClass, $bp + 1 );
        return $this->generateClassCodeWithNamespace($ns, $modelName, $type);
    }


    /**
     * Generate record action class dynamically.
     *
     * generate( 'PluginName' , 'News' , 'Create' );
     * will generate:
     * PluginName\Action\CreateNews
     *
     * @param string $ns
     * @param string $modelName
     * @param string $type
     *
     * @return string class code
     */
    function generateClassCodeWithNamespace( $ns , $modelName , $type )
    {
        $actionClass  = $type . $modelName;
        $actionFullClass = $ns . '\\Action\\'  . $actionClass;
        if( $this->cache && $code = apc_fetch( 'action:' . $actionFullClass ) ) {
            return (object) array(
                'action_class' => $actionFullClass,
                'code' => $code
            );
        }

        $recordClass  = $ns . '\\Model\\' . $modelName;
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
        if( $this->cache ) {
            apc_store('action:' . $actionFullClass , $code)
                or die('Can not store action class code.');
        }
        return (object) array( 
            'action_class' => $actionFullClass,
            'code' => $code,
        );
    }


    function generateActionClassCode($namespaceName,$actionName) {
        $actionNamespace = $namespaceName . '\\Action';
        $actionClass = $actionNamespace . '\\' . $actionName;
        $code =<<<CODE
namespace $actionNamespace {
    use ActionKit\\Action;
    class $actionName extends Action 
    {

        function schema() {

        }

        function run() {
            return \$this->success('Success!!');
        }

    }
}
CODE;
        return (object) array(
            'action_class' => $actionClass,
            'code' => $code,
        );
    }


}


