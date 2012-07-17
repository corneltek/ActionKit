<?php
namespace ActionKit;
use Exception;
use FormKit;
use ActionKit\Param;
use ActionKit\Result;
use Universal\Http\HttpRequest;
use InvalidArgumentException;
use IteratorAggregate;

abstract class Action implements IteratorAggregate
{
    public $currentUser;

    /**
     * @var array
     */
    public $args = array();   // post,get args for action


    /**
     * @var ActionKit\Result
     */
    public $result; // action result


    /**
     * @var ActionKit\Param[string Prama name]
     */
    public $params = array();


    /**
     * @public Universal\Http\HttpRequest request object
     */
    public $request;



    /**
     * @public array filter out fields (blacklist)
     */
    public $filterOutFields;


    /**
     * @public array take these fields only.
     */
    public $takeFields;

    /**
     * Constructing Action objects
     *
     * @param array $args The request arguments
     * @param mixed $currentUser
     */
    function __construct( $args = array() , $currentUser = null ) 
    {
        $this->request = new HttpRequest;
        $this->args = $args;
        $this->result = new Result;
        if( $currentUser ) {
            $this->currentUser = $currentUser;
        }

        // initialize parameter objects
        $this->schema();

        $this->args = $this->_filterArguments($args);

        // load param values from $arguments
        $overlap = array_intersect_key($this->args,$this->params);
        foreach( $overlap as $name => $val ) {
            $this->getParam($name)->value($val);
        }
        
        $this->result->args( $this->args ); // save request arguments
        $this->init();
    }


    protected function takes($fields) {
        $args = func_get_args();
        if( count($args) > 1 ) {
            $this->takeFields = (array) $args;
        } else {
            $this->takeFields = (array) $fields;
        }
        return $this;
    }

    function _filterArguments($args) {
        // find immutable params and unset them
        foreach( $this->params as $name => $param ) {
            if( $param->immutable ) {
                unset($args[$name]);
            }
        }

        if( $this->takeFields ) {
            // take these fields only
            return array_intersect_key( $args , array_fill_keys($this->takeFields,1) );
        }
        elseif( $this->filterOutFields ) {
            return array_diff_key( $args , array_fill_keys($this->filterOutFields,1) );
        }
        return $args;
    }

    /**
     * For Schema, Setup filter out fields,
     * When filterOut fields is set, 
     * Action will filter out those columns when executing action
     * Action View will skip rendering these column
     *
     * @param array $fields Field names
     */
    protected function filterOut($fields) 
    {
        $args = func_get_args();
        if( count($args) > 1 ) {
            $this->filterOutFields = (array) $args;
        } else {
            $this->filterOutFields = (array) $fields;
        }
        return $this;
    }




    /**
     * Run parameter validator to validate argument.
     *
     * @param string $name is a parameter name
     */
    protected function validateParam( $name )
    {
        // skip __ajax_request field
        if( $name === '__ajax_request' )
            return;

        if( ! isset($this->params[ $name ] ) ) {
            return;
            // just skip it.
            $this->result->addValidation( $name, array( 'invalid' => "Contains invalid arguments: $name" ));
            return true;
        }

        $param = $this->params[ $name ];
        $ret = (array) $param->validate( @$this->args[$name] );
        if( is_array($ret) ) {
            if( $ret[0] ) { // success
                # $this->result->addValidation( $name, array( "valid" => $ret[1] ));
            } else {
                $this->result->addValidation( $name, array( 'invalid' => @$ret[1] ));  // $ret[1] = message
                return true;
            }
        } else {
            throw new \Exception("Unknown validate return value of $name => " . $this->getName() );
        }
        return false;
    }

    /**
     * Run validates
     *
     * Foreach parameters, validate the parameter through validateParam method.
     *
     * @return bool error flag, returns TRUE on error.
     */
    function runValidate()
    {
        /* it's different behavior when running validation for create,update,delete,
         *
         * for generic action, just traverse all params. */
        $error = false;
        foreach( $this->params as $name => $param ) {
            $hasError = $this->validateParam( $name );
            if( $hasError )
                $error = true;
        }

        if( $error )
            $this->result->error( _('Validation Error') );
        return $error;
    }


    /**
     * Run preinit method of each param
     */
    function runPreinit()
    {
        // foreach is always faster than array_map
        foreach( $this->params as $param ) {
            $param->preinit( $this->args );
        }
    }


    /**
     * Run init method of each param
     */
    function runInit()
    {
        foreach( $this->params as $param ) {
            $param->init( $this->args );
        }
    }

    function isAjax()
    {  
        return isset( $_REQUEST['__ajax_request'] );
    }


    function invoke() 
    {
        /* run column methods */
        // XXX: merge them all...
        $this->runPreinit();
        if( $this->runValidate() )  // if found error, return false;
            return false;

        $this->runInit();
        $this->beforeRun();
        $ret = $this->run();
        $this->afterRun();
        return $ret;
    }

    function __invoke() 
    {
        return $this->invoke();
    }


    /* **** value getters **** */

    /**
     * Get Action name
     *
     * @return string
     */
    function getName()
    {
        $sig = $this->getSignature();
        $pos = strpos( $sig, '::Action::' );
        return substr( $sig , $pos + strlen('::Action::') );
    }

    function params($all = false) 
    {
        return $this->getParams($all);
    }

    function getParams( $all = false ) {
        $self = $this;
        if( $all ) {
            return $this->params;
        }

        if ( $this->takeFields ) {
            return array_intersect_key($this->params, array_fill_keys($this->takeFields,1) );  // find white list
        }
        elseif( $this->filterOutFields ) {
            return array_diff_key( $this->params, array_fill_keys($this->filterOutFields,1) ); // diff keys by blacklist
        } 
        return $this->params;
    }

    function getParam( $field ) 
    {
        return isset($this->params[ $field ])
                ? $this->params[ $field ]
                : null;
    }

    function hasParam( $field ) 
    {
        return isset($this->params[ $field ]);
    }

    function removeParam($field) 
    {
        if( isset($this->params[$field]) ) {
            $param = $this->params[$field];
            unset($this->params[$field]);
            return $param;
        }
    }


    /**
     * Return column widget object
     *
     * @param string $field field name
     *
     * @return FormKit\Widget
     */
    function widget($field, $widgetClass = null)
    {
        return $this->getParam($field)->createWidget( $widgetClass );
    }


    /**
     * Create and get displayable widgets 
     *
     * @param boolean $all get all parameters ? or filter paramemters
     */
    function getWidgets($all = false) 
    {
        $widgets = array();
        foreach( $this->getParams() as $param ) {
            $widgets[] = $param->createWidget();
        }
        return $widgets;

    }

    /**
     * Get current user
     */
    function getCurrentUser() 
    {
        if( $this->currentUser )
            return $this->currentUser;
    }


    /**
     * Set current user
     *
     * @param mixed Current user object.
     */
    function setCurrentUser( $user ) 
    {
        $this->currentUser = $user;
    }


    /**
     * Pass current user object to check permission.
     *
     * @return bool 
     */
    function currentUserCan( $user ) 
    {
        return $this->record->currentUserCan( $this->type , $this->args , $user );
    }



    /**
     * Set/Get argument
     *
     * @param string $name Argument key
     * @param mixed  $value (optional)
     *
     * @return mixed Argument value
     */
    function arg( $name )
    {
        $args = func_get_args();
        if( 1 === count($args) ) {
            return isset($this->args[ $name ]) ?
                         $this->args[ $name ]  : null;
        }
        elseif( 2 === count($args) ) {
            // set value
            return $this->args[ $name ] = $args[1];
        }
        else { die('arg error.'); }
    }


    /**
     * @return array
     */
    function getArgs() 
    {
        return $this->args; 
    }

    /**
     * TODO: we should use the file payload from Universal\Http\HttpRequest.
     *
     * @return array
     */
    function getFile( $name )
    {
        return isset($_FILES[ $name ])
                ? $_FILES[ $name ]
                : null;
    }

    /**
     * Set argument
     *
     * @param string $name argument key.
     * @param mixed $value argument value.
     *
     * @return this
     */
    function setArgument($name,$value) 
    { 
        $this->args[ $name ] = $value ; 
        return $this; 
    }


    /**
     * Set arguments
     *
     * @param array
     */
    function setArgs($args) 
    { 
        $this->args = array_merge($this->args , $args );
        return $this; 
    }


    /**
     * Define a param object from Action,
     *
     * Note: when using this method, a param that is already 
     * defined will be override.
     *
     * @param string $field Field name
     * @param string $type Field Type (will be Param Type)
     *
     * @return ActionKit\Param
     *
     *     $this->param('username'); // use ActionKit\Param
     *     $this->param('file', 'file' ); // use ActionKit\Param\File
     *     $this->param('image', 'image' ); // use ActionKit\Param\Image
     *
     */
    function param( $field , $type = null ) 
    {
        // default column class
        $class = 'ActionKit\\Param';
        if( $type ) {
            $class = ( $type[0] !== '+' ) 
                ? $class . '\\' . ucfirst($type)
                : substr($type,1);
        }

        if( ! class_exists($class,true) ) { // trigger spl class autoloader to load class file.
            throw new Exception("Action param($field): column class $class not found.");
        }
        return $this->params[ $field ] = new $class( $field , $this );
    }

    /**
     * Action schema is defined here.
     */
    function schema() 
    {

    }


    /**
     * Initialize action
     */
    function init()
    {

    }



    /**
     * Add data to result object
     *
     * @param string $key
     * @param mixed $val
     */
    function addData( $key , $val )
    {
        $this->result->addData( $key , $val );
    }



    function beforeRun() {  }

    function afterRun()  {  }

    /**
     * Run method, contains the main logics
     *
     **/
    function run() 
    {
        return true;
    }


    /**
     * Complete action field 
     *
     * @param string $field field name
     * */
    function complete( $field ) {
        $param = $this->getParam( $field );
        if( ! $param )
            die( 'action param not found.' );
        $ret = $param->complete();

        if( ! is_array( $ret ) )
            throw new Exception( "Completer doesnt return array. [type,list]\n" );

        // [ type , list ]
        $this->result->completion( $field , $ret[0], $ret[1] );
    }

    /**
     * Returns Action result, result is empty before running.
     *
     * @return ActionKit\Result
     */
    function getResult() 
    {
        return $this->result; 
    }


    /**
     * Redirect 
     *
     * @param string $path
     */
    function redirect( $path ) {

        /* for ajax request, we should redirect by json result,
         * for normal post, we should redirect directly. */
        if( $this->isAjax() ) {
            $this->result->redirect( $path );
            return;
        }
        else {
            header( 'Location: ' . $path );
            exit(0);
        }
    }


    /**
     * Redirect to path with a delay
     *
     * @param string $path
     * @param integer $secs
     */
    function redirectLater( $path , $secs = 1 )
    {
        if( $this->isAjax() ) {
            // XXX: more support.
            $this->result->redirect( $path );
            return;
        } else {
            header("Refresh: $secs; url=$path");
        }
    }

    /**
     * Create an Action View instance for Action.
     *
     *      ->asView()
     *      ->asView('ViewClass')
     *      ->asView(array( .. view options ..))
     *      ->asView('ViewClass', array( .. view options ..))
     *
     * @param string $class View class
     * @param array $attributes View options
     *
     * @return ActionKit\View\BaseView View object
     */
    function asView()
    {
        $options = array();
        $class = 'ActionKit\View\StackView';
        $args = func_get_args();

        // got one argument
        if( count($args) < 2 && isset($args[0]) ) {
            if( is_string($args[0]) ) {
                $class = $args[0];
            } elseif( is_array($args[0]) ) {
                $options = $args[0];
            }
        }
        elseif( count($args) == 2 ) {
            $class = $args[0];
            $options = $args[1];
        }
        return new $class($this, $options);
    }



    /**
     * Get action signature, this signature is for dispatching
     *
     * @return string Signature string
     */
    function getSignature()
    {
        return str_replace( '\\' , '::' , get_class($this) );
    }


    /**
     * Render widget 
     *
     * @param string $name column name
     * @param string $type Widget type, Input, Password ... etc
     * @param array $attrs Attributes
     * @return string HTML string
     */
    function renderWidget( $name , $type = null , $attrs = array() )
    {
        return $this->getParam( $name )->createWidget($type,$attrs)->render();
    }



    /**
     * Render column with field view class
     *
     * @param string $name column name
     * @param string $fieldViewClass
     * @param array $attrs 
     */
    function renderField( $name )
    {
        $args = func_get_args();
        $fieldViewClass = 'ActionKit\FieldView\DivFieldView';
        $attrs = array();
        if( count($args) == 2 ) {
            if( is_string($args[1]) ) {
                $fieldViewClass = $args[1];
            } elseif( is_array($args[1]) ) {
                $attrs = $args[1];
            }
        }
        elseif( count($args) == 3 ) {
            $fieldViewClass = $args[1];
            $attrs = $args[2];
        }
        $param = $this->getParam($name);
        $view = new $fieldViewClass($param);
        $view->setWidgetAttributes($attrs);
        return $view->render();
    }


    /**
     * Render the label of a action parameter
     *
     * @param string $name parameter name
     * @param array $attrs
     */
    function renderLabel( $name , $attrs = array() ) 
    {
        $label = $this->getParam( $name )->createLabelWidget();
        return $label->render( $attrs );
    }


    /**
     * A quick helper for rendering multiple fields
     *
     * @param string[] $fields Field names
     * @return string HTML string
     */
    function renderWidgets( $fields , $type = null, $attributes = array() )
    {
        $html = '';
        foreach( $fields as $field ) {
            $html .= $this->getParam($field)->render(null,$attributes) . "\n";
        }
        return $html;
    }

    /**
     * Render submit button widget
     *
     * @param array $attrs Attributes
     * @return string HTML string
     */
    function renderSubmitWidget($attrs = array() )
    {
        $submit = new FormKit\Widget\SubmitInput;
        return $submit->render($attrs);
    }



    /**
     * Render Button wigdet HTML
     *
     * @param array $attrs Attributes
     * @return string HTML string
     */
    function renderButtonWidget($attrs = array() )
    {
        $button = new FormKit\Widget\ButtonInput;
        return $button->render($attrs);
    }


    /**
     * Render action hidden field for signature
     *
     *      <input type="hidden" name="action" value="User::Action::UpdateUser"/>
     *
     * @return string Hidden input HTML
     */
    function renderSignatureWidget($attrs = array() )
    {
        $hidden = new FormKit\Widget\HiddenInput('action', 
                array( 'value' => $this->getSignature() ));
        return $hidden->render( $attrs );
    }


    /**
     * Render a field or render all fields,
     *
     * Note: this was kept for old version templates.
     *
     * @param string $name  field name (optional, when omit this, Action renders all fields)
     * @param array $attrs  field attributes
     * @return string HTML string
     */
    function render( $name = null , $attrs = array() ) 
    {
        if( $name ) {
            if( $widget = $this->widget( $name ) ) {
                return $widget->render( $attrs );
            } else {
                throw new Exception("parameter $name is not defined.");
            }
        }
        else {
            /* Render all widgets */
            $html = '';
            foreach( $this->params as $param ) {
                $html .= $param->render( $attrs );
            }
            return $html;
        }
    }

    function __set($name,$value) 
    {
        if( $param = $this->getParam( $name ) ) {
            $param->value = $value;
        }
        else {
            throw new InvalidArgumentException("Parameter $name not found.");
        }
    }

    function __isset($name)
    {
        return isset($this->params[$name]);
    }

    function __get($name)
    {
        return $this->getParam($name);
    }


    function getIterator() { 
        return new ArrayIterator($this->params);
    }

    /** 
     * Report success
     *
     * @param string $message Success message
     * @param mixed $data
     */
    function success( $message , $data = null ) {
        $this->result->success( $message );
        if( $data )
            $this->result->mergeData( $data );
        return true;
    }

    /**
     * Report error
     *
     * @param string $message Error message
     */
    function error( $message ) {
        $this->result->error( $message );
        return false;
    }

}

