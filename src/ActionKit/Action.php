<?php
namespace ActionKit;
use Exception;
use FormKit;
use ActionKit\Param;
use ActionKit\Result;
use Universal\Http\HttpRequest;
use Universal\Http\FilesParameter;
use InvalidArgumentException;
use IteratorAggregate;

class Action implements IteratorAggregate
{
    public $currentUser;

    public $nested = true;

    public $relationships = array();

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
     * @var Universal\Http\HttpRequest request object
     */
    public $request;

    /**
     * @var array filter out fields (blacklist)
     */
    public $filterOutFields;

    /**
     * @var array take these fields only.
     */
    public $takeFields;

    /**
     * @var boolean enable validatation ?
     */
    public $enableValidation = true;


    /**
     * @var array mix-in instances
     */
    public $mixins = array();


    /**
     * @var array Converted & Fixed $_FILES
     */
    public $files = array();

    /**
     * Constructing Action objects
     *
     * @param array $args        The request arguments
     * @param mixed $currentUser
     */
    public function __construct( $args = array() , $currentUser = null )
    {
        // always fix $_FILES
        if ( isset($_FILES) && ! empty($_FILES) ) {
            $this->files = \Universal\Http\FilesParameter::fix_files_array($_FILES);
        }

        if ( ! is_array($args) ) {
            throw new Exception('Action arguments of ' . get_class($this) . ' is not an array.');
        }

        if ( $currentUser ) {
            $this->currentUser = $currentUser;
        }

        $this->request = new HttpRequest;
        $this->result  = new Result;
        $this->mixins = $this->mixins();

        $this->preinit();
        foreach( $this->mixins as $mixin ) {
            $mixin->preinit();
        }

        // initialize parameter objects
        $this->schema();

        // intiailize schema from mixin classes later, 
        // so that we can use mixin to override the default options.
        foreach( $this->mixins as $mixin ) {
            $mixin->schema();
        }

        // use the schema definitions to filter arguments
        $this->args = $this->_filterArguments($args);

        if ( $relationId = $this->arg('__nested') ) {
            $this->setParamNamesWithIndex($relationId);
        }

        // load param values from $arguments
        $overlap = array_intersect_key($this->args,$this->params);
        foreach ($overlap as $name => $val) {
            $this->getParam($name)->value($val);
        }

        // action & parameters initialization
        // ===================================
        //
        // call the parameter preinit method to initialize
        // foreach is always faster than array_map
        foreach ($this->params as $param) {
            $param->preinit( $this->args );
        }


        // call the parameter init method
        foreach ($this->params as $param) {
            $param->init( $this->args );
        }
        // user-defined init script
        $this->init();


        // call the parameter init method
        foreach ($this->params as $param) {
            $param->postinit( $this->args );
        }



        $this->postinit();
        foreach( $this->mixins as $mixin ) {
            $mixin->postinit();
        }

        // save request arguments
        $this->result->args( $this->args );
    }


    public function mixins() {
        return array( 
            /* new MixinClass( $this, [params] ) */
        );
    }



    /**
     * Rewrite param names with index, this method is for
     * related records. e.g.
     *
     * relationId[ index ][name] = value
     * relationId[ index ][column2] = value
     *
     * @param string $key
     * @param string $index
     *
     * @return string index number
     */
    public function setParamNamesWithIndex($key, $index = null)
    {
        // The default index key for rendering field index name.
        //
        // if the record is loaded, use the primary key as identity.
        // if not, use timestamp simply, hope seconds is enough.
        if (! $index) {
            $index = ( $this->record && $this->record->id )
                ? $this->record->id
                : md5(microtime());
        }
        foreach ($this->params as $name => $param) {
            $param->name = sprintf('%s[%s][%s]', $key, $index, $param->name);
        }

        return $index;
    }

    public function takes($fields)
    {
        $args = func_get_args();
        if ( count($args) > 1 ) {
            $this->takeFields = (array) $args;
        } else {
            $this->takeFields = (array) $fields;
        }

        return $this;
    }

    public function _filterArguments($args)
    {
        // find immutable params and unset them
        foreach ($this->params as $name => $param) {
            if ($param->immutable) {
                unset($args[$name]);
            }
        }
        if ($this->takeFields) {
            // take these fields only
            return array_intersect_key( $args , array_fill_keys($this->takeFields,1) );
        } elseif ($this->filterOutFields) {
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
    public function filterOut($fields)
    {
        $args = func_get_args();
        if ( count($args) > 1 ) {
            $this->filterOutFields = (array) $args;
        } else {
            $this->filterOutFields = (array) $fields;
        }

        return $this;
    }

    public function invalidField($n, $message) {
        $this->result->addValidation($n, array(
            'valid' => false,
            'message' => $message,
            'field' => $n,
        ));
    }

    public function requireArg($n) {
        $v = $this->arg($n);
        if ( $v === null || $v === "" ) {
            $param = $this->getParam($n);
            $this->result->addValidation($n, array(
                'valid' => false,
                'message' => __(Messages::get('param.required'), $param ? $param->getLabel() : $n ),
                'field' => $n,
            ));
            return false;
        }
        return true;
    }

    public function requireArgs() {
        $ns = func_get_args();
        $satisfied = true;
        foreach( $ns as $n ) {
            if ( false === $this->requireArg($n) ) {
                $satisfied = false;
            }
        }
        return $satisfied;
    }



    /**
     * Run parameter validator to validate argument.
     *
     * @param string $name is a parameter name
     */
    public function validateParam( $name )
    {
        // skip __ajax_request field
        if ( $name === '__ajax_request' )
            return true;

        if ( ! isset($this->params[ $name ] ) ) {
            return true;

            // just skip it.
            $this->result->addValidation( $name, array(
                'valid' => false,
                'message' => "Contains invalid arguments: $name",
                'field' => $name,
            ));
            return true;
        }

        $param = $this->params[ $name ];

        /*
         * $ret contains:
         *
         *    [ boolean pass, string message ]
         *
         * or 
         *
         *    [ boolean pass ]
         */
        $ret = (array) $param->validate( @$this->args[$name] );
        if ( is_array($ret) ) {
            if ($ret[0]) { // success
                # $this->result->addValidation( $name, array( "valid" => $ret[1] ));
            } else {
                $this->result->addValidation( $name, array(
                    'valid' => false,
                    'message' => @$ret[1],
                    'field' => $name,
                ));  // $ret[1] = message
                return false;
            }
        } else {
            throw new \Exception("Unknown validate return value of $name => " . $this->getName() );
        }
        return true;
    }

    /**
     * Run validates
     *
     * Foreach parameters, validate the parameter through validateParam method.
     *
     * @return bool pass flag, returns FALSE on error.
     */
    public function runValidate()
    {
        /* it's different behavior when running validation for create,update,delete,
         *
         * for generic action, just traverse all params. */
        $foundError = false;
        foreach ($this->params as $name => $param) {
            if ( false === $this->validateParam( $name ) ) {
                $foundError = true;
            }
        }

        // we do this here because we need to validate all param(s)
        if ($foundError) {
            $this->result->error( _(Messages::get('validation.error') ) );
            return false;
        }

        // OK
        return true;
    }



    public function isAjax()
    {
        return isset( $_REQUEST['__ajax_request'] );
    }



    /**
     * Invoke is a run method wraper
     */
    public function invoke()
    {
        /* run column methods */
        // XXX: merge them all...
        $this->beforeRun();
        foreach( $this->mixins as $mixin ) {
            $mixin->beforeRun();
        }

        if ( $this->enableValidation && false === $this->runValidate() ) {  // if found error, return true;
            return false;
        }
        if ( false === $this->run() ) {
            return false;
        }

        foreach( $this->mixins as $mixin ) {
            if ( false === $mixin->run() ) {
                return false;
            }
        }

        if ( false === $this->afterRun() ) {
            return false;
        }
        foreach( $this->mixins as $mixin ) {
            if ( false === $mixin->afterRun() ) {
                return false;
            }
        }
        return true;
    }

    public function __invoke()
    {
        return $this->invoke();
    }


    /* **** value getters **** */

    /**
     * Get Action name
     *
     * @return string
     */
    public function getName()
    {
        $sig = $this->getSignature();
        $pos = strpos( $sig, '::Action::' );

        return substr( $sig , $pos + strlen('::Action::') );
    }

    public function params($all = false)
    {
        return $this->getParams($all);
    }

    public function getParams( $all = false )
    {
        $self = $this;
        if ($all) {
            return $this->params;
        }
        if ($this->takeFields) {
            return array_intersect_key($this->params, array_fill_keys($this->takeFields,1) );  // find white list
        } elseif ($this->filterOutFields) {
            return array_diff_key($this->params, array_fill_keys($this->filterOutFields,1) ); // diff keys by blacklist
        }

        return $this->params;
    }

    public function getParam( $field )
    {
        return isset($this->params[ $field ])
                ? $this->params[ $field ]
                : null;
    }

    public function hasParam( $field )
    {
        return isset($this->params[ $field ]);
    }

    public function removeParam($field)
    {
        if ( isset($this->params[$field]) ) {
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
    public function widget($field, $widgetClass = null)
    {
        return $this->getParam($field)->createWidget( $widgetClass );
    }


    /**
     * Create and get displayable widgets
     *
     * @param boolean $all get all parameters ? or filter paramemters
     */
    public function getWidgets($all = false)
    {
        $widgets = array();
        foreach ( $this->getParams($all) as $param ) {
            $widgets[] = $param->createWidget();
        }

        return $widgets;

    }


    public function getWidgetsByNames($names, $all = false)
    {
        $widgets = array();
        foreach ($names as $name) {
            if ( $param = $this->getParam($name) ) {
                $widgets[] = $param->createWidget();
            }
        }

        return $widgets;
    }

    /**
     * Get current user
     */
    public function getCurrentUser()
    {
        if ( $this->currentUser )

            return $this->currentUser;
    }


    /**
     * Set current user
     *
     * @param mixed Current user object.
     */
    public function setCurrentUser( $user )
    {
        $this->currentUser = $user;
    }


    /**
     * Pass current user object to check permission.
     *
     * @return bool
     */
    public function currentUserCan( $user )
    {
        return $this->record->currentUserCan( $this->type , $this->args , $user );
    }



    /**
     * Set/Get argument
     *
     * @param string $name  Argument key
     * @param mixed  $value (optional)
     *
     * @return mixed Argument value
     */
    public function arg( $name )
    {
        $args = func_get_args();
        if ( 1 === count($args) ) {
            return isset($this->args[ $name ]) ?
                         $this->args[ $name ]  : null;
        } elseif ( 2 === count($args) ) {
            // set value
            return $this->args[ $name ] = $args[1];
        } else { die('arg error.'); }
    }


    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * TODO: we should use the file payload from Universal\Http\HttpRequest.
     *
     * @return array
     */
    public function getFile( $name )
    {
        if ( isset($this->files[$name]) ) {
            return $this->files[$name];
        }
    }

    public function hasFile($name)
    {
        if ( isset($this->files[$name])
            && isset($this->files[$name]['name'])
            && $this->files[$name]['name'])
        {
            return $this->files[$name];
        }
    }

    /**
     * Set argument
     *
     * @param string $name  argument key.
     * @param mixed  $value argument value.
     *
     * @return this
     */
    public function setArgument($name,$value)
    {
        $this->args[ $name ] = $value ;
        return $this;
    }

    public function setArg($name, $value)
    {
        $this->args[ $name ] = $value ;
        return $this;
    }


    /**
     * Set arguments
     *
     * @param array
     */
    public function setArgs($args)
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
     * @param string $type  Field Type (will be Param Type)
     *
     * @return ActionKit\Param
     *
     *     $this->param('username'); // use ActionKit\Param
     *     $this->param('file', 'file' ); // use ActionKit\Param\File
     *     $this->param('image', 'image' ); // use ActionKit\Param\Image
     *
     */
    public function param( $field , $type = null )
    {
        // default column class
        $class = 'ActionKit\\Param';
        if ($type) {
            $class = ( $type[0] !== '+' )
                ? $class . '\\' . ucfirst($type)
                : substr($type,1);
        }

        if ( ! class_exists($class,true) ) { // trigger spl class autoloader to load class file.
            throw new Exception("Action param($field): column class $class not found.");
        }

        return $this->params[ $field ] = new $class( $field , $this );
    }

    /**
     * Action schema is defined here.
     */
    public function schema() { }

    public function preinit() {  }

    public function init() { }

    public function postinit() {  }



    /**
     * Add data to result object
     *
     * @param string $key
     * @param mixed  $val
     */
    public function addData( $key , $val )
    {
        $this->result->addData( $key , $val );
    }


    public function beforeRun() {  }

    public function afterRun()  {  }

    /**
     * Run method, contains the main logics
     *
     **/
    public function run()
    {
        return true;
    }


    /**
     * Complete action field
     *
     * @param string $field field name
     * */
    public function complete( $field )
    {
        $param = $this->getParam( $field );
        if ( ! $param )
            die( 'action param not found.' );
        $ret = $param->complete();
        if ( ! is_array( $ret ) )
            throw new Exception( "Completer doesnt return array. [type,list]\n" );

        // [ type , list ]
        $this->result->completion( $field , $ret[0], $ret[1] );
    }

    /**
     * Returns Action result, result is empty before running.
     *
     * @return ActionKit\Result
     */
    public function getResult()
    {
        return $this->result;
    }


    /**
     * Redirect
     *
     * @param string $path
     */
    public function redirect( $path )
    {

        /* for ajax request, we should redirect by json result,
         * for normal post, we should redirect directly. */
        if ( $this->isAjax() ) {
            $this->result->redirect( $path );

            return;
        } else {
            header( 'Location: ' . $path );
            exit(0);
        }
    }


    /**
     * Redirect to path with a delay
     *
     * @param string  $path
     * @param integer $secs
     */
    public function redirectLater( $path , $secs = 1 )
    {
        if ( $this->isAjax() ) {
            $this->result->redirect( $path, $secs );
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
     * @param string $class      View class
     * @param array  $attributes View options
     *
     * @return ActionKit\View\BaseView View object
     */
    public function asView()
    {
        $options = array();

        // built-in action view class
        $class = 'ActionKit\\View\\StackView';
        $args = func_get_args();

        // got one argument
        if ( count($args) < 2 and isset($args[0]) ) {
            if ( is_string($args[0]) ) {
                $class = $args[0];
            } elseif ( is_array($args[0]) ) {
                $options = $args[0];
            }
        } elseif ( count($args) == 2 ) {
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
    public function getSignature()
    {
        return str_replace( '\\' , '::' , get_class($this) );
    }


    /**
     * Render widget
     *
     * @param  string $name  column name
     * @param  string $type  Widget type, Input, Password ... etc
     * @param  array  $attrs Attributes
     * @return string HTML string
     */
    public function renderWidget( $name , $type = null , $attrs = array() )
    {
        return $this->getParam( $name )->createWidget($type,$attrs)->render();
    }



    /**
     * Render column with field view class
     *
     * renderField( 'name' )
     * renderField( 'name', FieldViewClass , WidgetAttributes )
     * renderField( 'name', WidgetAttributes )
     *
     * @param string $name           column name
     * @param string $fieldViewClass
     * @param array  $attrs
     */
    public function renderField( $name )
    {
        // the default field view class.
        $args = func_get_args();
        $fieldViewClass = 'ActionKit\FieldView\DivFieldView';
        $attrs = array();
        if ( count($args) == 2 ) {
            if ( is_string($args[1]) ) {
                $fieldViewClass = $args[1];
            } elseif ( is_array($args[1]) ) {
                $attrs = $args[1];
            }
        } elseif ( count($args) == 3 ) {
            if ( $args[1] )
                $fieldViewClass = $args[1];
            if ( $args[2] )
                $attrs = $args[2];
        }
        $param = $this->getParam($name);
        if (! $param) {
            throw new Exception( "Action param '$name' is not defined." );
        }
        $view = new $fieldViewClass($param);
        $view->setWidgetAttributes($attrs);

        return $view->render();
    }


    /**
     * Render the label of a action parameter
     *
     * @param string $name  parameter name
     * @param array  $attrs
     */
    public function renderLabel( $name , $attrs = array() )
    {
        $label = $this->getParam( $name )->createLabelWidget();

        return $label->render( $attrs );
    }


    /**
     * A quick helper for rendering multiple fields
     *
     * @param  string[] $fields Field names
     * @return string   HTML string
     */
    public function renderWidgets( $fields , $type = null, $attributes = array() )
    {
        $html = '';
        foreach ($fields as $field) {
            $html .= $this->getParam($field)->render(null,$attributes) . "\n";
        }

        return $html;
    }

    /**
     * Render submit button widget
     *
     * @param  array  $attrs Attributes
     * @return string HTML string
     */
    public function renderSubmitWidget($attrs = array() )
    {
        $submit = new FormKit\Widget\SubmitInput;

        return $submit->render($attrs);
    }



    /**
     * Render Button wigdet HTML
     *
     * @param  array  $attrs Attributes
     * @return string HTML string
     */
    public function renderButtonWidget($attrs = array() )
    {
        $button = new FormKit\Widget\ButtonInput;
        return $button->render($attrs);
    }



    /**
     * Shortcut method for creating signature widget
     */
    public function createSignatureWidget()
    {
        return new \FormKit\Widget\HiddenInput('action', array( 'value' => $this->getSignature() ));
    }


    /**
     * Render action hidden field for signature
     *
     *      <input type="hidden" name="action" value="User::Action::UpdateUser"/>
     *
     * @return string Hidden input HTML
     */
    public function renderSignatureWidget($attrs = array() )
    {
        $hidden = $this->createSignatureWidget();
        return $hidden->render( $attrs );
    }




    /**
     * Render a field or render all fields,
     *
     * Note: this was kept for old version templates.
     *
     * @param  string $name  field name (optional, when omit this, Action renders all fields)
     * @param  array  $attrs field attributes
     * @return string HTML string
     */
    public function render( $name = null , $attrs = array() )
    {
        if ($name) {
            if ( $widget = $this->widget( $name ) ) {
                return $widget->render( $attrs );
            } else {
                throw new Exception("parameter $name is not defined.");
            }
        } else {
            /* Render all widgets */
            $html = '';
            foreach ($this->params as $param) {
                $html .= $param->render( $attrs );
            }

            return $html;
        }
    }

    public function __set($name,$value)
    {
        if ( $param = $this->getParam( $name ) ) {
            $param->value = $value;
        } else {
            throw new InvalidArgumentException("Parameter $name not found.");
        }
    }

    public function __isset($name)
    {
        return isset($this->params[$name]);
    }

    public function __get($name)
    {
        return $this->getParam($name);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->params);
    }

    /**
     * Report success
     *
     * @param string $message Success message
     * @param mixed  $data
     */
    public function success( $message , $data = null )
    {
        $this->result->success( $message );
        if ( $data )
            $this->result->mergeData( $data );

        return true;
    }

    /**
     * Report error
     *
     * @param string $message Error message
     */
    public function error( $message )
    {
        $this->result->error( $message );

        return false;
    }

    public function __call($m , $args ) {
        foreach( $this->mixins as $mixin ) {
            if ( method_exists($mixin, $m) ) {
                return call_user_func_array(array($mixin,$m), $args);
            }
        }
    }



}
