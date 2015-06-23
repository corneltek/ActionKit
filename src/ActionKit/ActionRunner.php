<?php
namespace ActionKit;
use Exception;
use IteratorAggregate;
use ArrayAccess;
use ActionKit\Utils;
use ActionKit\Exception\InvalidActionNameException;
use ActionKit\Exception\ActionNotFoundException;
use ActionKit\Exception\UnableToWriteCacheException;
use ActionKit\Exception\UnableToCreateActionException;

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

    /**
     * @var array 
     * @DEPRECATED
     */
    protected $dynamicActions = array();


    /**
     * @var array The new action class generator pool
     */
    protected $dynamicActionsNew = array();

    protected $dynamicActionsWithTemplate = array();


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

        if ($options instanceof ServiceContainer) {
            $this->generator = $options['generator'];
        } else {
            if ( isset($options['cache_dir']) ) {
                $cacheDir = $options['cache_dir'];
            } else {
                $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
                if ( ! file_exists($cacheDir) ) {
                    mkdir($cacheDir, 0755, true);
                }
            }
            $this->generator = new ActionGenerator(array( 'cache' => true , 'cache_dir' => $cacheDir ));
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
    public function run($actionName, array $arguments = array() )
    {
        if ( ! Utils::validateActionName( $actionName ) ) {
            throw new InvalidActionNameException( "Invalid action name: $actionName." );
        }

        /* translate :: into php namespace */
        $class = Utils::toActionClass($actionName);

        /* register results into hash */
        if ( $action = $this->createAction($class, $arguments ) ) {
            $action->invoke();
            return $this->results[ $actionName ] = $action->getResult();
        }

        throw new UnableToCreateActionException( "Can not create action class $class" );
    }

    public function runWith(ActionRequest $request) 
    {
        if (!$request->getActionName()) {
            throw new InvalidActionNameException("");
        }
        if ( ! Utils::validateActionName( $request->getActionName() ) ) {
            throw new InvalidActionNameException( "Invalid action name: " . $request->getActionName() . ".");
        }

        return $this->run($request->getActionName(), $request->getArguments());
    }

    public function handleWith($stream, array $arguments = array())
    {
        try {
            $request = new ActionRequest($arguments);
            $result = $this->runWith($request);
            if ( $result && $request->isAjax()) {
                // Deprecated:
                // The text/plain seems work for IE8 (IE8 wraps the 
                // content with a '<pre>' tag.
                @header('Cache-Control: no-cache');
                @header('Content-Type: text/plain; Charset=utf-8');
                // Since we are using "textContent" instead of "innerHTML" attributes
                // we should output the correct json mime type.
                // header('Content-Type: application/json; Charset=utf-8');
                fwrite($stream, $result->__toString());
                return true;
            }
        } catch(Exception $e) {
            @header('HTTP/1.0 403');
            if ( $request->isAjax() ) {
                fwrite($stream, json_encode(array(
                        'error' => 1,
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                )));
            } else {
                throw $e;
            }
        }
    }

    public function loadClass($class) 
    {
        if ( isset($this->dynamicActionsWithTemplate[$class]) ) {
            $templateName = $this->dynamicActionsWithTemplate[$class]['templateName'];
            $actionArgs = $this->dynamicActionsWithTemplate[$class]['actionArgs'];
            if ( $this->generator->loadClassCache($class, $actionArgs) ) {
                return true;
            }

            $this->generator->generate3($templateName, $class, $actionArgs);

            require $cacheFile;
            return true;
        }
        if ( isset($this->dynamicActionsNew[$class]) ) {
            $actionArgs = $this->dynamicActionsNew[ $class ];

            if ( $this->generator->loadClassCache($class, $actionArgs) ) {
                return true;
            }

            $cacheFile = $this->getClassCacheFile($class, $actionArgs);

            $template = $this->generator->generate2($class, $actionArgs);
            if ( false === $template->writeTo($cacheFile) ) {
                throw new UnableToWriteCacheException("Can not write action class cache file: $cacheFile");
            }
            require $cacheFile;
            return true;
        }

        if ( isset( $this->dynamicActions[ $class ] ) ) {
            $actionArgs = $this->dynamicActions[ $class ];

            if ( $this->generator->loadClassCache($class, $actionArgs) ) {
                return true;
            }

            $cacheFile = $this->getClassCacheFile($class, $actionArgs);

            // Move the file fresh checking to loadClassCache method
            // if ( ! $loader->isFresh($actionArgs['template'], filemtime($cacheFile) ) ) {

            $code = $this->generator->generate($class, $actionArgs['template'], $actionArgs['variables']);
            if ( false === file_put_contents($cacheFile, $code) ) {
                throw new UnableToWriteCacheException("Can not write action class cache file: $cacheFile");
            }
            require $cacheFile;
            return true;
        }

        // DEPRECATED: backward compatible code
        if ( isset( $this->crudActions[$class] ) ) {
            // \FB::info('Generate action class: ' . $class);
            // Generate the crud action
            //
            // @see registerRecordAction method
            $args = $this->crudActions[$class];

            if ( $this->loadClassCache($className, $args) ) {
                return true;
            }

            $template = $this->generator->generateRecordActionNs( $args['ns'] , $args['model_name'], $args['type'] );
            $cacheFile = $this->getClassCacheFile($className, $args);
            $template->writeTo($cacheFile);
            require $cacheFile;
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

    public function registerActionWithTemplate($templateName, array $options)
    {
        $template = $this->generator->loadTemplate($templateName);
        $template->register($this, $options);
    }

    public function registerWithTemplate($targetActionClass, $templateName, array $actionArgs = array())
    {
        $this->dynamicActionsWithTemplate[$targetActionClass] = array(
            'templateName' => $templateName,
            'actionArgs' => $actionArgs
        );
    }

    /**
     * $this->register('App\Action\SortProductType',[ 
     *    'extends'    => '....',
     *    'properties' => [ 'recordClass' => .... ]
     * ]);
     */
    public function register($targetActionClass, $options = array() ) {
        $this->dynamicActionsNew[ $targetActionClass ] = $options;
    }

    /**
     * Add CRUD action class to pool, so we can generate these class later
     * if needed. (lazy)
     *
     *   - registerRecordAction( 'News' , 'News' , array('Create','Update') );
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
    public function registerRecordAction( $ns , $modelName , $types )
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

    public function registerCRUD( $ns , $modelName , $types )
    {
        $this->registerRecordAction( $ns, $modelName, $types );
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
            throw new ActionNotFoundException( "Action class not found: $class, you might need to setup action autoloader" );
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
