<?php
namespace ActionKit;
use Exception;
use IteratorAggregate;
use ArrayAccess;

/**
 * Run actions!
 *
 *
 *      full-qualified action name in web form:
 *              Yasumi::Action::Login
 *              Phifty::Action::Login
 *      names like "Login", "Signup" should refer to
 *              {App}::Action::Login or
 *              {App}::Action::Signup
 *
 *  $runner = ActionKit\ActionRunner::getInstance();
 *  $result = $runner->run();
 *  if ($result) {
 *      if ( $runner->isAjax() ) {
 *          echo $result;
 *      }
 *  }
 *
 * Iterator support:
 *
 *  foreach ($runner as $name => $result) {
 *
 *  }
 *
 */

class ActionRunner
    implements IteratorAggregate, ArrayAccess
{

    public $cacheDir;


    /**
     * @var array 
     * @DEPRECATED
     */
    protected $dynamicActions = array();


    /**
     * @var array The new action class generator pool
     */
    protected $dynamicActionsNew = array();



    /**
     * @var array Abstract CRUD action pool
     * @DEPRECATED
     */
    public $crudActions = array();

    /**
     * @var array Result pool
     */
    public $results = array();

    public $generator;

    public function __construct($options = array()) {

        if ( isset($options['cache_dir']) ) {
            $this->cacheDir = $options['cache_dir'];
        } else {
            $this->cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
            if ( ! file_exists($this->cacheDir) ) {
                mkdir($this->cacheDir, 0755, true);
            }
        }
        $this->generator = new ActionGenerator(array( 'cache' => true , 'cache_dir' => $this->cacheDir ));
    }

    /**
     * Check if action request, then dispatch the action class.
     *
     *
     * @param string  $actionName
     * @param array   $arguments
     * @return ActionKit\Result result array if there is such an action.
     * */
    public function run($actionName, $arguments = array() )
    {
        if ( $this->isInvalidActionName( $actionName ) ) {
            throw new Exception( "Invalid action name: $actionName." );
        }

        /* translate :: into php namespace */
        $class = $this->getActionClass( $actionName );


        /* register results into hash */
        if ( $action = $this->createAction( $class , $arguments ) ) {
            $action->invoke();
            return $this->results[ $actionName ] = $action->getResult();
        }



        throw new Exception( "Can not create action class $class" );
    }

    public function loadClass($class) 
    {
        if ( isset($this->dynamicActionsNew[$class]) ) {
            $actionArgs = $this->dynamicActionsNew[ $class ];
            $cacheFile = $this->getClassCacheFile($class, $actionArgs);
            $loader = $this->generator->getTwigLoader();
            // filemtime($cacheFile) 
            if (  ! file_exists($cacheFile) ) {
                $template = $this->generator->generate2($class, $actionArgs);
                if ( false === $template->writeTo($cacheFile) ) {
                    throw new Exception("Can not write action class cache file: $cacheFile");
                }
            }
            require $cacheFile;
            return true;
        }

        if ( isset( $this->dynamicActions[ $class ] ) ) {
            $actionArgs = $this->dynamicActions[ $class ];
            $cacheFile = $this->getClassCacheFile($class);

            $loader = $this->generator->getTwigLoader();
            if (  ! file_exists($cacheFile) || ! $loader->isFresh($actionArgs['template'], filemtime($cacheFile) ) ) {
                $code = $this->generator->generate($class, $actionArgs['template'], $actionArgs['variables']);
                if ( false === file_put_contents($cacheFile, $code) ) {
                    throw new Exception("Can not write action class cache file: $cacheFile");
                }
            }
            require $cacheFile;
            return true;
        }

        // DEPRECATED: backward compatible code
        if ( isset( $this->crudActions[$class] ) ) {
            // \FB::info('Generate action class: ' . $class);
            // Generate the crud action
            //
            // @see registerCRUD method
            $args = $this->crudActions[$class];
            $template = $this->generator->generateRecordActionNs( $args['ns'] , $args['model_name'], $args['type'] );
            return $this->loadClassTemplate($class, $template);
        }
    }

    public function registerAutoloader()
    {
        // use throw and not to prepend
        spl_autoload_register(array($this,'loadClass'),true, false);
    }


    /**
     * Register dynamic action by template.
     *
     * XXX: deprecated
     *
     * @param string $targetActionClass target action class name, full-qualified.
     * @param string $templateName      source template 
     * @param array $variables          template variables.
     */
    public function registerAction($targetActionClass, $templateName, $variables = array() )
    {
        $this->dynamicActions[ $targetActionClass ] = array(
            'template'  => $templateName,
            'variables' => $variables,
        );
    }


    /**
     * $this->registerAction2('App\Action\SortProductType',[ 
     *  'extends'    => '....',
     *  'properties' => [ 'recordClass' => .... ]
     * ]);
     */
    public function register($targetActionClass, $options = array() ) {
        $this->dynamicActionsNew[ $targetActionClass ] = $options;
    }

    /**
     * Add CRUD action class to pool, so we can generate these class later
     * if needed. (lazy)
     *
     *   - registerCRUD( 'News' , 'News' , array('Create','Update') );
     *
     * Which generates:
     *
     *    News\Action\CreateNews
     *    News\Action\UpdateNews
     *
     * @param string $ns        namespace name
     * @param string $modelName model name
     * @param array  $types     action types
     */
    public function registerCRUD( $ns , $modelName , $types )
    {
        foreach ( (array) $types as $type ) {
            $class = $ns . '\\Action\\' . $type . $modelName;
            $this->register( $class , [
                'extends' => "\\ActionKit\\RecordAction\\{$type}RecordAction",
                'properties' => [
                    'recordClass' => "$ns\\Model\\$modelName",
                ],
            ]);
            $this->crudActions[$class] = array(
                'ns'           => $ns,
                'type'         => $type,
                'model_name'   => $modelName,
            );
        }
    }

    public function isInvalidActionName( $actionName )
    {
        return preg_match( '/[^A-Za-z0-9:]/i' , $actionName  );
    }

    public function isFullQualifiedName( $actionName )
    {
        return strpos( $actionName, '::' ) != -1;
    }

    public function isAjax()
    {
        return isset($_REQUEST['__ajax_request']);
    }

    /**
     * Convert action signature into the actual full-qualified class name.
     *
     * This method replaces "::" charactors with "\" from action signature string.
     *
     * @param string $actionName
     */
    public function getActionClass( $actionName )
    {
        // replace :: with '\'
        return str_replace( '::' , '\\' , $actionName );
    }



    /**
     * Return the cache path of the class name
     *
     * @param string $className
     * @return string path
     */
    public function getClassCacheFile($className, $params = array())
    {
        $chk = empty($params) ? '' : md5(serialize($params));
        return $this->cacheDir . DIRECTORY_SEPARATOR . str_replace('\\','_',$className) . $chk . '.php';
    }


    /**
     *
     * @param string $className
     * @param ClassTemplate the class template object
     */
    public function loadClassTemplate($className, $template)
    {
        $cacheFile = $this->getClassCacheFile($className);
        if ( file_exists($cacheFile) ) {
            require $cacheFile;
            return true;
        }

        // generate cache file
        $template->writeTo($cacheFile);
        return require $cacheFile;
    }



    /**
     * Create action object
     *
     * @param string $class
     */
    public function createAction( $class , $args = array() )
    {
        $args = array_merge( $_REQUEST , $args );

        if ( isset($args['__ajax_request']) ) {
            unset( $args['__ajax_request'] );
        }

        if ( isset($args['action']) ) {
            unset( $args['action'] );
        }

        if ( class_exists($class, true) ) {
            return new $class( $args );
        }

        $this->loadClass($class);

        // call spl to autoload the class
        if ( ! class_exists($class,true) ) {
            throw new Exception( "Action class not found: $class, you might need to setup action autoloader" );
        }

        return new $class( $args );
    }

    /**
     * Get all results
     *
     * @return ActionKit\Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Get Action result by action name
     *
     * @param string $name action name (format: App::Action::ActionName)
     */
    public function getResult( $name )
    {
        return isset($this->results[ $name ]) ?
                $this->results[ $name ] : null;

    }

    /**
     * Check if we have action result
     *
     * @param string $name Action name
     */
    public function hasResult($name)
    {
        return isset($this->results[$name]);
    }

    public function setResult($name, $result) {
        $this->results[$name] = $result;
    }

    public function removeResult($name)
    {
        unset( $this->results[$name] );
    }

    public static function getInstance()
    {
        static $self;
        if ( $self )

            return $self;
        return $self = new static;
    }

    // Implement IteratorAggregate methods
    public function getIterator()
    {
        return new ArrayIterator($this->results);
    }

    // Implement ArrayAccess
    public function offsetSet($name,$value)
    {
        $this->results[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($this->results[ $name ]);
    }
    
    public function offsetGet($name)
    {
        return $this->results[ $name ];
    }
    
    public function offsetUnset($name)
    {
        unset( $this->results[$name] );
    }
    
}
