<?php
namespace ActionKit;
use Exception;

class Result 
{
    public $type;  // success, error, valid, invalid, completion, redirect
    public $args;  // arguments to actions.

    public $action;  // action name
    public $actionClass;

    public $validations = array();  // validation data


    /* main success message , error message */
    public $message;


    /* should we redirect ? this is usually needed in ajax */
    public $redirect;

    /* action can return data, for ajax applicatino */
    public $data = array();

    /* Completion data, (Only when doing completion) */
    public $completion;


    function __construct( $type = null ) 
    {
        if( $type ) 
            $this->type($type);
    }

    function type( $type ) 
    {
        // check type
        if( $type != "completion"
            && $type != "success"
            && $type != "error"
            && $type != "valid"
            && $type != "invalid"
            && $type != "redirect" )
            throw new Exception( _('Wrong ActionResult Type, Line ') . __LINE__ );
        $this->type = $type;
    }

    /* is_* helper functions */
    function isSuccess() 
    { 
        return 'success' === $this->type;
    }

    function isError() 
    {
        return 'error' === $this->type;
    }

    function isValidation() 
    {
        return 'valid' === $this->type 
                || 'invalid' === $this->type;
    } 

    function isCompletion() 
    {  
        return 'completion' === $this->type;
    }


    function getData() 
    {
        return $this->data;
    }

    function getMessage() 
    {
        return $this->message;
    }

    function action( $action )
    {
        $this->actionClass = get_class( $action );
        $this->action = $action;
        return $this;
    }

    function completion( $field, $type, $list , $style = 'default' )
    {
        $this->type('completion');
        $this->checkCompType( $type );
        $this->checkCompList( $list );
        $this->completion = array(
            "field" => $field,
            "style" => $style,
            "list" => $list,
            "type" => $type
        );
        return $this;
    }

    private function checkCompType( $type )
    {
        if( 'dict' !== $type
            && 'list' !== $type ) {
            throw new Exception( _('Invalid completion type, should be "dict" or "list".') );
        }
    }

    private function checkCompList( $list )
    {
        if( ! is_array( $list ) ) 
            throw new Exception( _('Invalid completion data type, should be array.') );
    }

    function completer( $field, $func, $args, $style = 'default' )
    {
        $this->type("completion");
        $ret = call_user_func_array( $func , $args );
        $comp_type = $ret[0];
        $comp_list = $ret[1];
        $this->checkCompType( $comp_type );
        $this->checkCompList( $comp_list );
        $this->completion = array( 
            "field" => $field,
            "style" => $style,
            "type" => $comp_type,
            "list" => $comp_list
        );
        return $this;
    }


    function gatherInvalidMsgs()
    {
        $errors = array();
        foreach( $this->validations as $field => $attrs ) {
            if( @$attrs["invalid"] && is_string( @$attrs['invalid'] ) ) {
                array_push( $errors , $attrs["invalid"] );
            }
        }
        return $errors;
    }

    function redirect( $path )
    {
        $this->redirect = $path; return $this; 
    }

    function args( $args ) 
    {
        $this->args = $args; 
        return $this; 
    }

    function data( $data ) 
    { 
		$this->data = $data; 
		return $this; 
    }

	function addData( $key, $value )
	{
		$this->data[ $key ] = $value;
		return $this;
	}

	function mergeData( $data )
	{
		$this->data = array_merge( $this->data , $data );
		return $this;
	}

    function success( $message = null )
    {
        $this->type = 'success';
        if( $message )
            $this->message = $message;
        return $this;
    }

    function error( $message = null )
    {
        $this->type = 'error';
        if( $message )
            $this->message = $message;
        return $this;
    }

    function valid( $message = null )
    {
        $this->type = 'valid';
        if( $message )
            $this->message = $message;
        return $this;
    }

    function invalid( $message = null )
    {
        $this->type = 'invalid';
        if( $message )
            $this->message = $message;
        return $this;
    }

    function addValidation( $field , $attrs )
    {
        $this->validations[ $field ] = $attrs;
        return $this;
    }

    function getJsonData()
    {
        $ret = array( );

        if( $this->args ) {
            $ret['args'] = $this->args;
        }

        isset($this->type) or die("ActionResult type undefined.");

        $ret[ $this->type ] = true;

        if( $this->message )
            $ret[ 'message' ] = $this->message;

        if( $this->type == "success" ) {
            $ret['data'] = $this->data;
        }
        elseif ( $this->type == "error" ) {
            $ret['data']  = $this->data;
            $ret['validations'] = $this->validations;
        }
        elseif ( $this->type == "valid" ) {
            $ret['validations'] = $this->validations;
        }
        elseif ( $this->type == "invalid" ) {
            $ret['validations'] = $this->validations;
        }
        elseif ( $this->type == "completion" ) {
            $ret = array_merge( $ret , $this->completion );
        }

        if( $this->redirect ) 
            $ret[ 'redirect'] = $this->redirect;

        return $ret;
    }

    public function __toString()
    {
        $data = $this->getJsonData();
        return json_encode( $data );
    }

}
