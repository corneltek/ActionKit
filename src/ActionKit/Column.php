<?php
namespace ActionKit;
use CascadingAttribute;


class Column extends CascadingAttribute
{
    /* action object referenece */
    public $action;

    /* action param name */
    public $name;

    /* action param type */
    public $type;

    /* is a required column ? */
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


    public $renderAs;

    /* default render Widget */
    public $widgetClass = '\Phifty\FormWidget\Text';

    public $validator;

    public $sanitizer;

    public $filter;

    public $completer;

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
    public function renderAs( $type ) 
    {
        $this->widgetClass = 
            ( $type[0] == '\\' ) ? $type : '\Phifty\FormWidget\\' . $type;
    }



    /**
     * A simple widget factory for Action Column
     *
     * @param string $widgetClass Widget Class.
     */
    public function createWidget( $widgetClass = null , $attributes = null )
    {
        $widgetNs = 'FormKit\\Widget\\';
        $class = $widgetClass ?: $this->widgetClass;

        // convert attributes into widget style attributes
        $newAttributes = array();
        if( $this->validValues ) {
            $newAttributes['options'] = $this->validValues;
        }
        if( $this->immutable ) {
            $newAttributes['readonly'] = true;
        }
        if( $this->defaultValue ) {
            $newAttributes['value'] = $this->defaultValue;
        }

        // if it's not a full-qualified class name
        // we should concat class name with default widget namespace
        if( '+' !== $class[0] ) {
            $class = $widgetNs . $class;
        }
        return new $class( $this->name , $newAttributes );
    }

#      protected function _newWidget()
#      {
#          $class = $this->widgetClass;
#          if( ! $class )
#              $class = 'Phifty\FormWidget\Text';  # default class
#          return new $class( $this );
#      }
    /* render as other widget */
#      public function renderWidget( $type , $attrs = array() ) 
#      {
#          $this->renderAs( $type );
#          return $this->render( $attrs );
#      }
#      public function render( $attrs = array() )
#      {
#          /* it's full-qualified name */
#          // $widgetClass = ( $widgetType[0] == '\\' ) ? $widgetType : '\Phifty\Widget\\' . $widgetType;
#          // $widget = new $class( $this );
#          return $this->_newWidget()->render($attrs);
#      }


}

