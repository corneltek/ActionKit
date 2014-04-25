<?php
namespace ActionKit;
use UniversalCache;
use Twig_Loader_Filesystem;
use Twig_Environment;
use ReflectionClass;
use ClassTemplate\ClassTemplate;

/**
 * Action Generator Synopsis
 *
 *    $generator = new ActionGenerator(array(
 *          'cache' => true,                 // this enables apc cache.
 *
 *
 *          // currently we only use APC
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

    public $templateDirs = array();

    public $templates = array();

    public function __construct( $options = array() )
    {
        if ( isset($options['cache_dir']) ) {
            $this->cacheDir = $options['cache_dir'];
        } else {
            $this->cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
            if ( ! file_exists($this->cacheDir) ) {
                mkdir($this->cacheDir, 0755, true);
            }
        }
    }

    public function addTemplateDir($path)
    {
        $this->templateDirs[] = $path;
    }


    /**
     * This method generates class code based on the template file from Twig
     * 
     */
    // $template = $gen->generate($class, $actionArgs['template'], $actionArgs['variables']);
    // $variables['base_class']
    // $variables['record_class']
    public function generate($targetClassName, $template = null, $variables = array())
    {
        $parts = explode("\\",$targetClassName);
        $variables['target'] = array();
        $variables['target']['classname'] = array_pop($parts);
        $variables['target']['namespace'] = join("\\", $parts);
        $twig = $this->getTwig();
        return $twig->render($template, $variables);
        /*
        TODO: here is the new code to generate class....

        $baseClass = $variables['base_class'];
        $recordClass = $variables['record_class'];
        $classTemplate = new ClassTemplate($targetClassName);
        $classTemplate->useClass($baseClass);

        // this is to support backward-compatible for classes like 'SortablePlugin\\Action\\SortRecordAction'
        $_p = explode('\\',$baseClass);
        $baseClassName = end($_p);

        $classTemplate->extendClass($baseClassName);
        $classTemplate->addProperty('recordClass',$variables['record_class']);
        return $classTemplate->render();
         */
    }

    public function getTwigLoader() {

        static $loader;
        if ( $loader ) {
            return $loader;
        }
        // add ActionKit built-in template path
        $loader = new Twig_Loader_Filesystem($this->templateDirs);
        $loader->addPath( __DIR__ . DIRECTORY_SEPARATOR . 'Templates', "ActionKit" );
        return $loader;
    }

    public function getTwig()
    {
        static $twig;
        if ( $twig ) {
            return $twig;
        }
        $loader = $this->getTwigLoader();
        $env = new Twig_Environment($loader, array(
            'cache' => $this->cacheDir ? $this->cacheDir : false,
        ));
        return $env;
    }



    /**
     * Given a model class name, split out the namespace and the model name.
     *
     * @param string $modelClass full-qualified model class name
     * @param string $type action type
     */
    public function generateClassCode( $modelClass , $type )
    {
        $ps = explode('\\', ltrim($modelClass) );
        $modelName = array_pop($ps);
        $ns = join("\\", $ps);
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
     * @return ClassTemplate
     */
    public function generateClassCodeWithNamespace( $modelNs , $modelName , $type )
    {
        $modelNs = ltrim($modelNs,'\\');
        $actionClass  = $type . $modelName;

        // here we translate App\Model\Book to App\Action\CreateBook or something
        $actionNs = str_replace('Model','Action', $modelNs);
        $actionFullClass = ltrim($actionNs . '\\' . $actionClass, '\\');

        // the original ns is the model namespace
        $recordClass  = ltrim($modelNs . '\\' . $modelName, '\\');
        $baseAction   = $type . 'RecordAction';

        $classTemplate = new ClassTemplate($actionFullClass);
        $classTemplate->useClass("\\ActionKit\\RecordAction\\$baseAction");
        $classTemplate->extendClass("\\ActionKit\\RecordAction\\$baseAction");
        $classTemplate->addProperty('recordClass',$recordClass);
        return $classTemplate;
    }


    /**
     * Generate a generic action class code with an empty schema, run methods
     *
     * @param string $namespaceName the parent namespace of the 'Action' namespace.
     * @param string $actionName    the action class name (short class name)
     * @return ClassTemplate
     */
    public function generateActionClassCode($namespaceName,$actionName)
    {
        $classTemplate = new ClassTemplate("$namespaceName\\Action\\$actionName");
        $classTemplate->useClass("\\ActionKit\\Action");
        $classTemplate->extendClass("Action");
        $classTemplate->addMethod('public','schema', [] , '');
        $classTemplate->addMethod('public','run', [] , 'return $this->success("Success!");');
        return $classTemplate;
    }

}
