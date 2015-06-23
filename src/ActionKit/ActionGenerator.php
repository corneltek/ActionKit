<?php
namespace ActionKit;
use ActionKit\ActionTemplate\IActionTemplate;
use Exception;
use UniversalCache;
use ReflectionClass;
use ClassTemplate\TemplateClassFile;

/**
 * Action Generator Synopsis
 * 
 *    $generator = new ActionGenerator(array(
 *          // currently we only use APC
 *          'cache_dir' => 'phifty/cache',
 *    ));
 *    $generator->registerTemplate(new ActionKit\ActionTemplate\FileBasedActionTemplate);
 *    $className = 'User\Action\BulkDeleteUser';
 *
 *    $cacheFile = $generator->generate('FileBasedActionTemplate', 
 *        $className, 
 *        array(
 *            'template' => '@ActionKit/RecordAction.html.twig',
 *            'variables' => array(
 *                'record_class' => 'User\\Model\\User',
 *                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
 *            )
 *        )
 *    );
 *
 *    require $cacheFile;
 *
 */
class ActionGenerator
{

    public $cacheDir;

    public $templates = array();

    public function __construct(array $options = array() )
    {
        if ( isset($options['cache_dir']) ) {
            $this->cacheDir = $options['cache_dir'];
        } else {
            $this->cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
            if (! file_exists($this->cacheDir)) {
                mkdir($this->cacheDir, 0755, true);
            }
        }
    }

    /**
     * The new generate method to generate action class with action template
     */
    public function generate($templateName, $class, array $actionArgs = array())
    {
        $actionTemplate = $this->loadTemplate($templateName);
        $cacheFile = $this->getClassCacheFile($class, $actionArgs);
        return $actionTemplate->generate($class, $cacheFile, $actionArgs);
    }

    /**
     * The new generate method to generate action class
     *
     * @param string $targetClassName
     * @param array  $options
     *
     * @synopsis
     *
     *    $template = $g->generate2('ProductBundle\\Action\\SortProductImage', [ 
     *              'extends' => 'SortablePlugin\\Action\\SortRecordAction',
     *              'constants' => [
     *                  ...
     *              ],
     *              'properties' => [
     *                  'recordClass' => 'ProductBundle\\Model\\ProductImage',
     *                  'fields' => '....',
     *              ],
     *    ]);
     *    $template->addMethod(...); // extra operations
     *
     *
     */
    public function generate2($targetClassName, $options = array() )
    {
        $templateClassFile = new TemplateClassFile($targetClassName);

        // General use statement
        $templateClassFile->useClass('\\ActionKit\\Action');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\BaseRecordAction');
        /*
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\CreateRecordAction');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\UpdateRecordAction');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\DeleteRecordAction');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\BulkDeleteRecordAction');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\BulkCreateRecordAction');
        */

        if ( isset($options['extends']) ) {
            $templateClassFile->extendClass($options['extends']);
        }
        if ( isset($options['properties']) ) {
            foreach( $options['properties'] as $name => $value ) {
                $templateClassFile->addProperty($name, $value);
            }
        }
        if ( isset($options['constants']) ) {
            foreach( $options['constants'] as $name => $value ) {
                $templateClassFile->addConst($name, $value);
            }
        }
        return $templateClassFile;
    }

    /**
     * Given a model class name, split out the namespace and the model name.
     *
     * @param string $modelClass full-qualified model class name
     * @param string $type action type
     *
     *
     *  $g->generateRecordAction( 'App\Model\User', 'Create' ); // generates App\Action\CreateUser
     *
     */
    public function generateRecordAction($modelClass , $type )
    {
        list($modelNs, $modelName) = explode('\\Model\\', $modelClass);
        return $this->generateRecordActionNs($modelNs, $modelName, $type);
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
     * @param string $type  RecordAction Type
     *
     * @return ClassTemplate
     */
    public function generateRecordActionNs($ns, $modelName , $type)
    {
        $ns = ltrim($ns,'\\');
        // here we translate App\Model\Book to App\Action\CreateBook or something
        $actionFullClass = $ns . '\\Action\\' . $type . $modelName;
        $recordClass  = $ns . '\\Model\\' . $modelName;
        $baseAction   = $type . 'RecordAction';
        return $this->generate2($actionFullClass, [ 
            'extends' => '\\ActionKit\\RecordAction\\' . $baseAction,
            'properties' => [ 
                'recordClass' => $recordClass,
            ],
        ]);
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
        $classTemplate = $this->generate2("$namespaceName\\Action\\$actionName", [ 
            'extends' => 'Action',
        ]);
        $classTemplate->addMethod('public','schema', [] , '');
        $classTemplate->addMethod('public','run', [] , 'return $this->success("Success!");');
        return $classTemplate;
    }



    /**
     * Return the cache path of the class name
     *
     * @param string $className
     * @return string path
     */
    public function getClassCacheFile($className, array $params = array())
    {
        $chk = ! empty($params) ? md5(serialize($params)) : '';
        return $this->cacheDir . DIRECTORY_SEPARATOR . str_replace('\\','_',$className) . $chk . '.php';
    }

    /**
     * Load the class cache file
     *
     * @param string $className the action class
     */
    public function loadClassCache($className, array $params = array()) {
        $file = $this->getClassCacheFile($className, $params);
        if ( file_exists($file) ) {
            require $file;
            return true;
        }
        return false;
    }

    /**
     * register action template
     * @param object $template the action template object
     */
    public function registerTemplate(IActionTemplate $template)
    {
        $this->templates[$template->getTemplateName()] = $template;
    }

    /**
     * load action template object with action template name
     * @param string $templateName the action template name
     * @return object action template object
     */
    public function loadTemplate($templateName)
    {
        if ( isset($this->templates[$templateName])) {
            return $this->templates[$templateName];
        } else {
            throw new Exception("load $templateName template failed.");
        }
    }
}
