<?php
namespace ActionKit;
use ActionKit\ActionTemplate;
use Exception;
use Exception\UndefinedTemplateException;
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
 *    $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());
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
        }
        if (! file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
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
    public function registerTemplate($templateName, ActionTemplate\ActionTemplate $template)
    {
        $this->templates[$templateName] = $template;
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
            throw new UndefinedTemplateException("load $templateName template failed.");
        }
    }
}
