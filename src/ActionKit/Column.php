<?php
namespace ActionKit;
use CascadingAttribute;
use FormKit;


class Column extends CascadingAttribute
{
    /**
     * @var ActionKit\Action action object referenece
     * */
    public $action;

    /**
     * @var string action param name 
     */
    public $name;

    /**
     * @var string action param type 
     */
    public $type;

    /**
     * @var boolean is a required column ? 
     */
    public $required;

    /* current value ? */
    public $value;

    /* valid values */
    public $validValues;

    /* valid pair values */
    public $validPairs;

    /* default value */
    public $default;

    /* is immutable ? */
    public $immutable;

    /* refer class *? */
    public $refer;

    /* default render Widget */
    public $widgetClass = 'TextInput';

    /* default widget namespace */
    public $widgetNamespace = 'FormKit\\Widget\\';

    public $validator;

    public function __construct( $name , $action = null ) 
    {
        $this->name = $name;
        $this->action = $action;
        $this->build();
    }

    public function build()
    {

    }

    /**
     * We dont save any value here,
     * The value's should be passed from outside.
     *
     * Supported validations:
     *   * required
     *
     * @param mixed $value
     *
     * @return array|true Returns error with message or true
     */
    function validate( $value )
    {

        /* if it's file type , should read from $_FILES , not from the args of action */
        if( $this->type == 'file' ) {
            if( $this->required && ! isset($_FILES[ $this->name ]['tmp_name']) ) {
                return array(false, __('Field %1 is required.' , $this->getLabel()  ) );
            }
        } else {
            if( $this->required && ! isset($_REQUEST[ $this->name ]) && ! $this->default ) {
                return array(false, __('Field %1 is required.' , $this->getLabel()  ) );
            }
        }

        if( $this->validator ) {
            $func = $this->validator;
            return $func( $value );
        }
        return true;
    }

    public function preinit( & $args )
    {

    }

    public function init( & $args ) 
    {

    }

    public function getLabel()
    {
        if( $this->label )
            return $this->label;
        return ucfirst($this->name);
    }

    /**************************
     * Widget related methods
     **************************/
    public function renderAs( $type ) {
        $this->widgetClass = $type;
        return $this;
    }

    public function render($attributes = null) {
        $widget = $this->createWidget( null , $attributes );
        return $widget->render();
    }

    /**
     * A simple widget factory for Action Column
     *
     * @param string $widgetClass Widget Class.
     */
    public function createWidget( $widgetClass = null , $attributes = null ) {
        $class = $widgetClass ?: $this->widgetClass;

        // convert attributes into widget style attributes
        $newAttributes = array();

        if( $label = $this->getLabel() ) {
            $newAttributes['label'] = $label;
        }
        if( $this->validValues ) {
            $newAttributes['options'] = $this->validValues;
        }
        if( $this->immutable ) {
            $newAttributes['readonly'] = true;
        }


        // for password input, we should not render value
        if( false === stripos( $class , 'Password' ) ) {
            if( $this->value ) {
                $newAttributes['value'] = $this->value;
            }
            elseif( $this->defaultValue ) {
                $newAttributes['value'] = $this->defaultValue;
            }
        }

        if( $this->placeholder ) {
            $newAttributes['placeholder'] = $this->placeholder;
        }

        // merge override attributes
        if( $attributes ) {
            $newAttributes = array_merge( $newAttributes , $attributes );
        }

        // if it's not a full-qualified class name
        // we should concat class name with default widget namespace
        if( '+' !== $class[0] ) {
            $class = $this->widgetNamespace . $class;
        }
        return new $class( $this->name , $newAttributes );
    }
}

