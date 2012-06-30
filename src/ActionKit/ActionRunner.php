<?php
namespace ActionKit;
use Exception;
use IteratorAggregate;
use ActionKit\RecordAction\BaseRecordAction;

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
 *  if( $result ) {
 *      if( $runner->isAjax() ) {
 *          echo $result;
 *      }
 *  }
 *
 * Iterator support:
 *
 *  foreach( $runner as $name => $result ) {
 *
 *  }
 *
 */

class ActionRunner
    implements IteratorAggregate
{
    /**
     * Abstract CRUD action pool 
     *
     * @var array
     */
    public $crudActions = array();

    /**
     * Result pool 
     *
     * @var array
     */
    public $results = array();

    /*
     * Check if action request, then dispatch the action class.
     *
     * @return return result array if there is such an action.
     * */
    public function run() 
    {
        if( isset($_REQUEST['action']) )
        {
            $actionName = $this->getCurrentActionName(); // without postfix "Action".

            if( $this->isInvalidActionName( $actionName ) )
                throw new Exception( "Invalid action name: $actionName." );

            /* translate :: into php namespace */
            $class = $this->getActionClass( $actionName );
            if( ! class_exists($class,true) && ! $this->isCRUDAction( $class ) ) {
                throw new Exception( "Action class not found: $actionName $class." );
            }

            /* register results into hash */
            return $this->results[ $actionName ] = $this->dispatch( $class );
        }
    }

    public function autoload($class) 
    {
        if( ! $this->isCRUDAction($class) )
            return false;

        /* check if action is in CRUD list */
        if( isset( $this->crudActions[$class] ) ) {
            // Generate the crud action
            //
            // @see registerCRUD method
            $gen = new ActionGenerator(array( 'cache' => true ));

            $args = $this->crudActions[$class];
            $code = $gen->generateClassCodeWithNamespace( $args['ns'], $args['model_name'], $args['type'] )->code;

            // TODO: eval is slower than require
            //       use a better code generator
            eval( $code );
            return true;
        }
    }

    public function registerAutoloader() 
    {
        spl_autoload_register(array($this,'autoload'));
    }

    /**
     * Add CRUD action class to pool, so we can generate these class later 
     * if needed. (lazy)
     *
     *   - registerCRUD( 'News' , 'News' , array('create') );
     * 
     * @param string $ns namespace name
     * @param string $modelName model name
     * @param array $types action types
     */
    public function registerCRUD( $ns , $modelName , $types ) 
    {
        foreach( (array) $types as $type ) {
            $class = $ns . '\Action\\' . $type . $modelName;
            $this->crudActions[$class] = array( 
                'ns'           => $ns,
                'type'         => $type,
                'model_name'   => $modelName,
            );
        }
    }

    public function registerCRUDFromModel( $model , $types ) 
    {
        $class = is_object($model) ? get_class($model) : $model;
        $nsParts = explode('\\',$class);
        if( count($nsParts) >= 3 ) {
            // XXX:
        }
    }

    public function isCRUDAction($class)
    {
        return isset( $this->crudActions[$class] );
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

    public function getCurrentActionName() 
    {
        return isset($_REQUEST['action']) ? 
            $_REQUEST['action'] : null;
    }


    /*
     * replace action name "::" charactors with "\"
     * */
    public function getActionClass( $actionName ) 
    {
        // replace :: with '\'
        return str_replace( '::' , '\\' , $actionName );
    }


    /**
     * Create action object
     *
     * @param string $class
     */
    public function createAction( $class ) 
    {
        $args = array_merge( array() , $_REQUEST );

        if( isset($args['__ajax_request']) ) {
            unset( $args['__ajax_request'] );
        }
        if( isset($args['action']) ) {
            unset( $args['action'] );
        }

        if( class_exists($class,true) )
            return new $class( $args );

        /* check if action is in CRUD list */
        if( isset( $this->crudActions[$class] ) ) {

            /* generate the crud action */
            $args = $this->crudActions[$class];

            // please see registerCRUD method
            $gen = new ActionGenerator(array( 'cache' => true ));
            $code = $gen->generateClassCodeWithNamespace( $args['ns'], $args['model_name'], $args['type'] )->code;

            // TODO: eval is slower than require
            //       use a better code generator
            eval( $code );
            return new $class( $_REQUEST );
        }
        return new $class( $_REQUEST );
    }

    public function dispatch( $class ) 
    {
        $act = $this->createAction( $class );
        if( ! $act ) {
            throw new Exception( "Can not create action class $class" );
        }
        $act();
        return $act->getResult();
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

    public function removeResult($name)
    {
        unset( $this->results[$name] );
    }


    static function getInstance()
    {
        static $self;
        if( $self )
            return $self;
        return $self = new static;
    }


    public function getIterator() 
    {
        return new ArrayIterator($this->results);
    }

}

