<?php
namespace ActionKit;
use Exception;

class Result 
{
    /**
     * @var string success, error
     */
    public $type;  // success, error, valid, invalid, completion, redirect

    /**
     * @var array argument array
     */
    public $args;  // arguments to actions.


    /**
     * @var string ActionKit\Action
     */
    public $action;  // action object

    /**
     * @var array Validation Results
     */
    public $validations = array();  // validation data


    /**
     * @var string main success message , error message
     */
    public $message;


    /**
     * @var string can descrbie the result.
     */
    public $desc;

    /**
     * @var boolean should we redirect ? this is usually needed in ajax 
     */
    public $redirect;




    /** 
     * @var array action can return data. 
     * */
    public $data = array();

    /**
     * @var array Completion data, (Only when doing completion) 
     */
    public $completion;

    /**
     *
     * @param string $type Action result type, 'success', 'error', 'valid', 'invalid', 'completion', 'redirect'
     */
    function __construct( $type = null ) 
    {
        if( $type ) {
            $this->type($type);
        }
    }


    /**
     * Set result type
     *
     * @param string $type
     */
    function type( $type ) 
    {
        if( in_array($type, array('success','completeion','error','valid','invalid','redirect')) ) {
            throw new Exception('Invalid result type.');
        }
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
        $this->action = $action;
        return $this;
    }

    function completion( $field, $type, $list , $style = 'default' )
    {
        $this->type = 'completion';
        $this->checkCompType( $type );
        $this->checkCompList( $list );
        $this->completion = array(
            'field' => $field,
            'style' => $style,
            'list' => $list,
            'type' => $type
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
        if( ! is_array( $list ) ) {
            throw new Exception( _('Invalid completion data type, should be array.') );
        }
    }

    function completer( $field, $func, $args, $style = 'default' )
    {
        $this->type = 'completion';
        $ret = call_user_func_array( $func , $args );
        $comp_type = $ret[0];
        $comp_list = $ret[1];
        $this->checkCompType( $comp_type );
        $this->checkCompList( $comp_list );
        $this->completion = array( 
            'field' => $field,
            'style' => $style,
            'type' => $comp_type,
            'list' => $comp_list
        );
        return $this;
    }


    /**
     * Transform validation messages into errors.
     *
     * @return array
     */
    function gatherInvalidMsgs()
    {
        $errors = array();
        foreach( $this->validations as $field => $attrs ) {
            if( @$attrs['invalid'] && is_string( @$attrs['invalid'] ) ) {
                array_push( $errors , $attrs['invalid'] );
            }
        }
        return $errors;
    }


    /**
     * Set redirect path
     *
     * @param string $path
     */
    public function redirect( $path )
    {
        $this->redirect = $path; 
        return $this; 
    }


    /**
     * Set arguments
     *
     * @param array $args
     */
    public function args( $args ) 
    {
        $this->args = $args; 
        return $this; 
    }


    /**
     * Set result description
     *
     * @param string $desc
     */
    public function desc($desc) {
        $this->desc = $desc;
        return $this;
    }


    /**
     * Set result data
     */
    public function data( $data , $val = null )
    { 
        if( is_array($data) ) {
            $this->data = $data; 
        } elseif( $val ) {
            $this->data[ $data ] = $val;
        }
        return $this; 
    }

    public function addData( $key, $value )
    {
        $this->data[ $key ] = $value;
        return $this;
    }

    public function mergeData( $data )
    {
        $this->data = array_merge( $this->data , $data );
        return $this;
    }

    public function success( $message = null )
    {
        $this->type = 'success';
        if( $message )
            $this->message = $message;
        return $this;
    }

    public function error( $message = null )
    {
        $this->type = 'error';
        if( $message )
            $this->message = $message;
        return $this;
    }

    public function valid( $message = null )
    {
        $this->type = 'valid';
        if( $message )
            $this->message = $message;
        return $this;
    }

    public function invalid( $message = null )
    {
        $this->type = 'invalid';
        if( $message )
            $this->message = $message;
        return $this;
    }

    public function addValidation( $field , $attrs )
    {
        $this->validations[ $field ] = $attrs;
        return $this;
    }

    public function toArray()
    {
        $ret = array();

        if( $this->args ) {
            $ret['args'] = $this->args;
        }

        isset($this->type) or die("ActionResult type undefined.");

        $ret[ $this->type ] = true;

        if( $this->desc ) {
            $ret['desc'] = $this->desc;
        }

        if( $this->message ) {
            $ret[ 'message' ] = $this->message;
        }

        if( 'success' === $this->type ) {
            $ret['data'] = $this->data;
        }
        elseif ( 'error' === $this->type ) {
            $ret['data']  = $this->data;
        }
        elseif ( 'completion' === $this->type ) {
            $ret = array_merge( $ret , $this->completion );
        }

        if( $this->validations )
            $ret['validations'] = $this->validations;

        if( $this->redirect ) {
            $ret['redirect'] = $this->redirect;
        }
        return $ret;
    }

    public function __toString()
    {
        return json_encode( $this->toArray(), JSON_HEX_TAG );
    }
}
