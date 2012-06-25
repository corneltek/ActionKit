<?php
namespace ActionKit;
use CascadingAttribute;
use FormKit;


class Param extends CascadingAttribute
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

    /* default widget attributes */
    public $widgetAttributes;

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

    public function getDefaultValue()
    {
        if( is_callable($this->default) ) {
            return call_user_func($this->default);
        }
        return $this->default;
    }

    /**************************
     * Widget related methods
     **************************/


    /**
     * Render action column as {Type}Widget, with extra options/attributes
     *
     *     $this->column('created_on')
     *         ->renderAs('DateInput', array( 'format' => 'yy-mm-dd' ))
     *
     * @param string $type Widget type
     * @param array $attributes
     *
     * @return self
     */
    public function renderAs( $type , $attributes = null ) {
        $this->widgetClass = $type;
        if( $attributes ) {
            $this->widgetAttributes = $attributes;
        }
        return $this;
    }


    /**
     * Render current parameter column to HTML
     *
     * @param array|null $attributes
     * @return string
     */
    public function render($attributes = null) {
        return $this->createWidget( null , $attributes )
            ->render();
    }


    public function createLabelWidget($widgetClass = null , $attributes = array() )
    {
        $class = $widgetClass ?: 'FormKit\Widget\Label';
        return new $class( $this->getLabel() );
    }

    /**
     * A simple widget factory for Action Column
     *
     * @param string $widgetClass Widget Class.
     * @param array  $attributes Widget attributes.
     * @return FormKit\Widget\BaseWidget
     */
    public function createWidget( $widgetClass = null , $attributes = array() ) {
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
            elseif( $this->default ) {
                $newAttributes['value'] = $this->getDefaultValue();
            }
        }

        if( $this->placeholder ) {
            $newAttributes['placeholder'] = $this->placeholder;
        }

        // merge override attributes
        if( $this->widgetAttributes ) {
            $newAttributes = array_merge( $newAttributes , $this->widgetAttributes );
        }
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

