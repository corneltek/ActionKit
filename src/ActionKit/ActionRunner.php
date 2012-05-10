<?php
namespace ActionKit;

/*

    ActionRunner:

        full-qualified action name in web form:

        Yasumi::Action::Login
        Phifty::Action::Login

        names like "Login", "Signup" should refer to 

        {App}::Action::Login or 
        {App}::Action::Signup


    $runner = ActionKit\ActionRunner::getInstance();
    $result = $runner->run();
    if( $result ) {
        if( $runner->isAjax() ) {
            echo $result;
        }
    }

*/
use Exception;
use Phifty\Singleton;

class ActionRunner extends Singleton
{
    /* abstract CRUD actions */
    public $crudActions = array();

    /* result pool */
	public $results = array();

    /*
     * Check if action request, then dispatch the action class.
     *
     * @return return result array if there is such an action.
     * */
    function run() 
    {
        if( $this->hasRequest() ) 
        {
            $actionName = $this->getCurrentActionName(); // without postfix "Action".

            if( $this->isInvalidActionName( $actionName ) )
                throw new \Exception( "Invalid action name: $actionName." );

            /* translate :: into php namespace */
            $class = $this->getActionClass( $actionName );

            if( ! $this->tryLoad( $class ) ) {
                if( ! $this->isCRUD( $class ) ) {
                    throw new \Exception( "Action class not found: $actionName $class." );
                }
            }

            /* register results into hash */
            $result = $this->dispatch( $class );
            return $this->results[ $actionName ] = $result;
        }
    }

    /* 
     * News model in News plugin.
     *
     * addCRUD( 'News' , 'News' , array('create') );
    */
    function addCRUD( $ns , $modelName , $types ) 
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


    function isCRUD($class)
    {
        return ( isset( $this->crudActions[$class] ) );
    }



    function isInvalidActionName( $actionName ) 
    {
        return preg_match( '/[^A-Za-z0-9:]/i' , $actionName  );
    }

    function isFullQualifiedName( $actionName )
    {
        return strpos( $actionName, '::' ) != -1;
    }

    function isAjax() 
    {
        return (bool) @$_REQUEST['__ajax_request'];
    }

    function hasRequest() 
    {
        return @$_REQUEST['action'];
    }

    function getCurrentActionName() 
    {
        return @$_REQUEST['action'];
    }


    /*
     * replace action name "::" charactors with "\"
     * */
    function getActionClass( $actionName ) 
    {
        // replace :: with '\'
        if( $this->isFullQualifiedName( $actionName ) ) {
            return str_replace( '::' , '\\' , $actionName );
        }
    }

    function tryLoad( $class ) 
    {
        // try to call autoload function
        // to load action class.
        spl_autoload_call( $class );

        # if class load success.
        if( class_exists( $class ) )
            return true;
        return false;
    }

    public function getAction( $class ) 
    {
        $args = array_merge( array() , $_REQUEST );

        if( isset($args['__ajax_request']) ) 
            unset( $args['__ajax_request'] );
        if( isset($args['action']) )
            unset( $args['action'] );

        if( class_exists($class) )
            return new $class( $args );

        /* check if action is in CRUD list */
        if( isset( $this->crudActions[$class] ) ) {

            

            /* generate the crud action */
            $args = $this->crudActions[$class];

            // please see addCRUD method
            $code = RecordAction::generate( $args['ns'] , $args['model_name'] , $args['type'] );
            eval( $code );
            return new $class( $_REQUEST );
        }

        return new $class( $_REQUEST );
    }

    function dispatch( $class ) 
    {
        $act = $this->getAction( $class );
        if( $act !== false ) {
            $act();
            return $act->getResult();
        }
    }

	function getResults()
	{
		return $this->results;
	}

	function getResult( $name )
	{
		return @$this->results[ $name ];
	}

}

?>
