<?php
namespace ActionKit;
use Exception;
use IteratorAggregate;
use ArrayAccess;
use ActionKit\Utils;
use ActionKit\ActionRequest;
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
    protected $pretreatments = array();

    /**
     * @var array Result pool
     */
    public $results = array();

    public $generator;

    public $cacheDir;

    protected $debug;

    protected $currentUser;


    /**
     * @param array $options
     *
     * Options:
     *
     *   'locale': optional, the current locale 
     *   'cache_dir': optional, the cache directory of generated action classes
     *   'generator': optional, the customized Generator object.
     *
     */
    public function __construct($options = array()) {

        $messagePool = MessagePool::getInstance();

        if ($options instanceof ServiceContainer) {

            // the cache_dir option is optional. if user provides one, we should use it.
            $this->cacheDir = isset($options['cache_dir']) ? $options['cache_dir'] : __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
            $this->generator = isset($options['generator']) ? $options['generator'] : new ActionGenerator;

            if (isset($options['locale'])) {
                $messagePool->loadByLocale($options['locale']);
            } else {
                $messagePool->loadByLocale('en'); // default to en
            }

        } else if ($options instanceof ActionGenerator) {

            $this->generator = $options;

        } else {

            // Default initializor

            if (isset($options['cache_dir'])) {
                $this->cacheDir = $options['cache_dir'];
            } else {
                $this->cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';
            }

            $this->generator = new ActionGenerator;

            if (isset($options['locale'])) {
                $messagePool->loadByLocale($options['locale']);
            } else {
                $messagePool->loadByLocale('en'); // default to en
            }
        }

        if ($this->cacheDir && ! file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function setDebug($debug = true)
    {
        $this->debug = $debug;
    }


    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Check if action request, then dispatch the action class.
     *
     *
     * @param string  $actionName
     * @param array   $arguments
     * @return ActionKit\Result result array if there is such an action.
     * */
    public function run($actionName, array $arguments = array(), ActionRequest $request = null )
    {
        if ( ! Utils::validateActionName( $actionName ) ) {
            throw new InvalidActionNameException( "Invalid action name: $actionName." );
        }

        /* translate :: into php namespace */
        $class = Utils::toActionClass($actionName);

        /* register results into hash */
        $action = $this->createAction($class, $arguments, $request);
        $action->invoke();
        return $this->results[ $actionName ] = $action->getResult();
    }

    public function runWithRequest(ActionRequest $request)
    {
        if (!$request->getActionName()) {
            throw new InvalidActionNameException("");
        }
        if ( ! Utils::validateActionName( $request->getActionName() ) ) {
            throw new InvalidActionNameException( "Invalid action name: " . $request->getActionName() . ".");
        }
        return $this->run($request->getActionName(), $request->getArguments(), $request);
    }


    /**
     * Run action request with a try catch block
     * return ajax response when __ajax_request is defined.
     *
     * @param resource $stream STDIN, STDOUT, STDERR, or any resource
     * @param array $arguments Usually $_REQUEST array
     * @param array $files  Usually $_FILES array
     * @return return true if it's an ajax response 
     */
    public function handleWith($stream, array $arguments = array(), array $files = null)
    {
        try {
            $request = new ActionRequest($arguments, $files);
            $result = $this->runWithRequest($request);
            if ($result && $request->isAjax()) {
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
        } catch (Exception $e) {
            @header('HTTP/1.1 403 Action API Error');
            if ($request->isAjax()) {
                if ($this->debug) {
                    fwrite($stream, json_encode(array(
                        'error' => 1,
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                    )));
                } else {
                    fwrite($stream, json_encode(array(
                        'error' => 1,
                        'message' => $e->getMessage(),
                    )));
                }
                return true;
            } else {
                throw $e;
            }
        }
    }

    /**
     * loadActionClass trigger the action class generation if the class doesn't
     * exist and loads the action class.
     *
     * @param string $class action class
     */
    public function loadActionClass($class) 
    {
        if (!isset($this->pretreatments[$class])) {
            return false;
        }

        $pretreatment = $this->pretreatments[$class];

        if ($this->loadClassCache($class, $pretreatment['arguments']) ) {
            return true;
        }

        $generatedAction = $this->generator->generate($pretreatment['template'], $class, $pretreatment['arguments']);

        $cacheFile = $this->getClassCacheFile($class, $pretreatment['arguments']);
        $generatedAction->requireAt($cacheFile);
        return true;
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

    public function registerAutoloader()
    {
        // use throw and not to prepend
        spl_autoload_register(array($this,'loadActionClass'),true, false);
    }


    /**
     * registerAction register actions by passing action config to ActionTemplate.
     *
     * @param string $actionTemplateName
     * @param array $templateArguments
     */
    public function registerAction($actionTemplateName, array $templateArguments)
    {
        $template = $this->generator->getTemplate($actionTemplateName);
        $template->register($this, $actionTemplateName, $templateArguments);
    }


    /**
     * register method registers the action class with specified action template name and its arguments
     *
     */
    public function register($targetActionClass, $actionTemplateName, array $templateArguments = array())
    {
        $this->pretreatments[$targetActionClass] = array(
            'template' => $actionTemplateName,
            'arguments' => $templateArguments,
        );
    }

    public function countOfPretreatments()
    {
        return count($this->pretreatments);
    }

    public function getPretreatments()
    {
        return $this->pretreatments;
    }

    public function getActionPretreatment($actionClass)
    {
        if (isset($this->pretreatments[$actionClass])) {
            return $this->pretreatments[$actionClass];
        }
    }

    public function isInvalidActionName( $actionName )
    {
        return preg_match( '/[^A-Za-z0-9:]/i' , $actionName  );
    }

    /**
     * Create action object from REQUEST
     *
     * @param string $class
     */
    public function createAction($class , array $args = array(), ActionRequest $request = null)
    {
        // Try to load the user-defined action
        if (!class_exists($class, true) ) {

            // load the generated action
            $this->loadActionClass($class);

            // Check the action class existence
            if ( ! class_exists($class,true) ) {
                throw new ActionNotFoundException( "Action class not found: $class, you might need to setup action autoloader" );
            }
        }
        $action = new $class($args, array(
            'request' => $request,
        ));
        $action->setCurrentUser($this->currentUser);
        return $action;
    }

    public function setCurrentUser($user)
    {
        $this->currentUser = $user;
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
        if ( $self ) {
            return $self;
        }
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
        unset($this->results[$name]);
    }
    
}
