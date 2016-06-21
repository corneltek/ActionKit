<?php
namespace ActionKit;
use ActionKit\Messages;
use ArrayAccess;
use Exception;
use InvalidArgumentException;


/**
 * This class defines the response properties of an action.
 *
 * @package ActionKit
 */
class Result implements ArrayAccess
{
    /**
     * @var array The internal stash array of the data presentation
     */
    protected $stash = [];


    /**
     * @var integer http response code, by default we return 200.
     */
    public $responseCode = 200;

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

    public $redirectDelay;

    /**
     * @var array action can return data.
     * */
    public $data = array();

    /**
     * @var array Completion data, (Only when doing completion)
     */
    public $completion;


    /**
     * @var mixed debug information
     */
    public $debug;

    /**
     *
     * @param string $type Action result type, 'success', 'error', 'valid', 'invalid', 'completion', 'redirect'
     */
    public function __construct( $type = null )
    {
        if ($type) {
            $this->type($type);
        }
    }

    /**
     * Set result type
     *
     * @param string $type
     */
    public function type( $type )
    {
        if ( in_array($type, array('success','completeion','error','valid','invalid','redirect')) ) {
            throw new Exception('Invalid result type.');
        }
        $this->type = $type;
    }

    /* is_* helper functions */
    public function isSuccess()
    {
        return 'success' === $this->type;
    }

    public function isError()
    {
        return 'error' === $this->type;
    }

    public function isValidation()
    {
        return 'valid' === $this->type
                || 'invalid' === $this->type;
    }

    public function isCompletion()
    {
        return 'completion' === $this->type;
    }

    public function getStashedData()
    {
        return $this->data;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function action( $action )
    {
        $this->action = $action;

        return $this;
    }

    public function completion( $field, $type, $list , $style = 'default' )
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
        if ('dict' !== $type
            && 'list' !== $type) {
            throw new Exception( _('Invalid completion type, should be "dict" or "list".') );
        }
    }

    private function checkCompList( $list )
    {
        if ( ! is_array( $list ) ) {
            throw new Exception( _('Invalid completion data type, should be array.') );
        }
    }

    public function completer( $field, $func, $args, $style = 'default' )
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
     * Add new validation entry
     *
     * This method doesn't set type to invalid or valid, it depends on the attributes.
     *
     * @return this
     */
    public function addValidation($field, array $attrs)
    {
        $this->validations[ $field ] = $attrs;
        return $this;
    }

    /**
     * Remove an validation
     */
    public function removeValidation($field)
    {
        unset($this->validations[$field]);
    }

    public function addInvalid($field, $message, $desc = null)
    {
        $this->type = 'invalid';
        $this->validations[$field] = [ 'valid' => false, 'message' => $message, 'desc' => $desc, 'field' => $field, ];
    }


    public function addValid($field, $message, $desc = null)
    {
        $this->validations[$field] = [ 'valid' => true, 'message' => $message, 'desc' => $desc, 'field' => $field, ];
    }



    /**
     * Check if some validation return failed.
     *
     * @return bool
     */
    public function hasInvalidMessages()
    {
        foreach ($this->validations as $field => $attrs) {
            if (array_key_exists('valid', $attrs) && $attrs['valid'] === false) {
                return true;
            }
        }
        return false;
    }



    /**
     * Set redirect path
     *
     * @param string $path
     */
    public function redirect( $path , $delaySeconds = 0 )
    {
        $this->redirect = $path;
        $this->redirectDelay = $delaySeconds;
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
    public function desc($desc)
    {
        $this->desc = $desc;

        return $this;
    }


    /**
     * Set debug information
     *
     * @param mixed $debug
     */
    public function debug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Set result data
     */
    public function data( $data , $val = null )
    {
        if (is_array($data)) {
            $this->data = $data;
        } else if ($data && $val) {
            if (is_string($val) || is_numeric($data)) {
                $this->data[ $data ] = $val;
            } else {
                throw new InvalidArgumentException("data key can only be integer or string");
            }
        } else {
            throw new InvalidArgumentException("Unsupported data type.");
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

    public function success($message = null, $responseCode = null)
    {
        $this->type = 'success';
        if ($message) {
            $this->message = $message;
        }
        if ($responseCode) {
            $this->responseCode = $responseCode;
        }
        return $this;
    }

    public function error($message = null, $responseCode = null)
    {
        $this->type = 'error';
        if ($message) {
            $this->message = $message;
        }
        if ($responseCode) {
            $this->responseCode = $responseCode;
        }
        return $this;
    }

    public function valid( $message = null )
    {
        $this->type = 'valid';
        if ($message) {
            $this->message = $message;
        }
        return $this;
    }

    public function invalid($message = null )
    {
        $this->type = 'invalid';
        if ($message) {
            $this->message = $message;
        }
        return $this;
    }


    public function toArray()
    {
        // this will copy the stash array
        $ret = array_merge([], $this->stash);

        $ret['code'] = $this->responseCode;

        if ($this->args) {
            $ret['args'] = $this->args;
        }

        isset($this->type) or die("ActionResult type undefined.");

        $ret[ $this->type ] = true;

        if ($this->desc) {
            $ret['desc'] = $this->desc;
        }

        if ($this->message) {
            $ret[ 'message' ] = $this->message;
        }

        if ($this->data) {
            $ret['data'] = $this->data;
        }

        if ('completion' === $this->type) {
            $ret = array_merge( $ret , $this->completion );
        }

        if (!empty($this->validations)) {
            $ret['validations'] = $this->validations;
        }

        if ($this->redirect) {
            $ret['redirect'] = $this->redirect;
            if ( $this->redirectDelay ) {
                $ret['delay'] = $this->redirectDelay;
            }
        }

        return $ret;
    }



    public function offsetSet($name,$value)
    {
        $this->stash[ $name ] = $value;
    }

    public function offsetExists($name)
    {
        return isset($this->stash[ $name ]);
    }

    public function offsetGet($name)
    {
        return $this->stash[ $name ];
    }

    public function offsetUnset($name)
    {
        unset($this->stash[$name]);
    }

    public function __toString()
    {
        return json_encode( $this->toArray(), JSON_HEX_TAG );
    }
}
