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
     */
    public $dynamicActions = array();



    /**
     * @var array Abstract CRUD action pool
     */
    public $crudActions = array();

    /**
     * @var array Result pool
     */
    public $results = array();

    public function __construct($options = array()) {
        if ( isset($options['cache_dir']) ) {
            $this->cacheDir = $options['cache_dir'];
        } else {
            $this->cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
            if ( ! file_exists($this->cacheDir) ) {
                mkdir($this->cacheDir, 0755, true);
            }
        }
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

    public function autoload($class)
    {
        /* check if action is in CRUD list */
        if ( ! isset( $this->crudActions[$class] ) ) {
            return false;
        }

        // \FB::info('Generate action class: ' . $class);

        // Generate the crud action
        //
        // @see registerCRUD method
        $gen = new ActionGenerator(array( 'cache' => true ));
        $args = $this->crudActions[$class];
        $template = $gen->generateClassCodeWithNamespace( $args['prefix'], $args['model_name'], $args['type'] );
        return $this->loadClassTemplate($class, $template);
    }

    public function registerAutoloader()
    {
        // use throw and not to prepend
        spl_autoload_register(array($this,'autoload'),true, false);
    }




    /**
     * Register dynamic action by template.
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
    public function registerCRUD( $prefixNs , $modelName , $types )
    {
        foreach ( (array) $types as $type ) {
            $class = $prefixNs . '\\Action\\' . $type . $modelName;
            $this->registerAction( $class , '@ActionKit/RecordAction.html.twig',array( 
                'record_class' => "$prefixNs\\Model\\$modelName",
                'base_class' => "ActionKit\\RecordAction\\{$type}RecordAction",
            ));

            $this->crudActions[$class] = array(
                'prefix'       => $prefixNs . '\\Model',
                'type'         => $type,
                'model_name'   => $modelName,
            );
        }
    }


    public function registerCRUDAction($class,$args)
    {
        throw new Exception("registerCRUDAction is deprecated.");
        // $this->crudActions[ $class ] = $args;
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
    public function getClassCacheFile($className) 
    {
        return $this->cacheDir . DIRECTORY_SEPARATOR . str_replace('\\','_',$className) . '.php';
    }


    /**
     *
     * @param string $className
     * @param ClassTemplate the class template object
     */
    public function loadClassTemplate($className, $template)
    {
        if ( class_exists($className, true) ) {
            return true;
        }

        $cacheFile = $this->getClassCacheFile($className);
        if ( file_exists($cacheFile) ) {
            require $cacheFile;
            return true;
        }

        // generate cache file
        file_put_contents($cacheFile, $template->render());
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

        if ( isset( $this->dynamicActions[ $class ] ) ) {
            $actionArgs = $this->dynamicActions[ $class ];
            $cacheFile = $this->getClassCacheFile($class);

            $gen = new ActionGenerator;
            $loader = $gen->getTwigLoader();
            if (  ! file_exists($cacheFile) || ! $loader->isFresh($actionArgs['template'], filemtime($cacheFile) ) ) {
                $template = $gen->generate($class, $actionArgs['template'], $actionArgs['variables']);
                if ( false === file_put_contents($cacheFile, $template->render() ) ) {
                    throw new Exception("Can not write action class cache file: $cacheFile");
                }
            }
            require $cacheFile;
            return new $class($args);
        }
        /* check if action is in CRUD list */
        else if ( isset( $this->crudActions[$class] ) ) {

            /* generate the crud action */
            $args = $this->crudActions[$class];

            // please see registerCRUD method
            $gen = new ActionGenerator(array( 'cache' => true ));
            $template = $gen->generateClassCodeWithNamespace( $args['prefix'], $args['model_name'], $args['type'] );
            $this->loadClassTemplate($class, $template);
            return new $class( $args );
        }


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
