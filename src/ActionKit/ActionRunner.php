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
    public $dynamicActions = array();

    /**
     * @var array Result pool
     */
    public $results = array();

    public $generator;

    public function __construct($options = array()) {

        if ($options instanceof ServiceContainer) {
            $this->generator = $options['generator'];
        } else {
            $options_generator = array();
            if ( isset($options['cache_dir']) ) {
                $options_generator['cache_dir'] = $options['cache_dir'];
            } 
            $this->generator = new ActionGenerator($options_generator);
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
        if ( isset($this->dynamicActions[$class]) ) {
            $templateName = $this->dynamicActions[$class]['actionTemplateName'];
            $actionArgs = $this->dynamicActions[$class]['actionArgs'];
            if ( $this->generator->loadClassCache($class, $actionArgs) ) {
                return true;
            }

            $this->generator->generate($templateName, $class, $actionArgs);
            return true;
        }
    }

    public function registerAutoloader()
    {
        // use throw and not to prepend
        spl_autoload_register(array($this,'loadClass'),true, false);
    }

    public function registerAction($actionTemplateName, array $options)
    {
        $template = $this->generator->loadTemplate($actionTemplateName);
        $template->register($this, $actionTemplateName, $options);
    }

    public function register($targetActionClass, $actionTemplateName, array $actionArgs = array())
    {
        $this->dynamicActions[$targetActionClass] = array(
            'actionTemplateName' => $actionTemplateName,
            'actionArgs' => $actionArgs
        );
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
