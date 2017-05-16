<?php
namespace ActionKit\Param;
use CascadingAttribute;
use FormKit;
use ActionKit\MessagePool;
use ActionKit\Action;
use DateTime;
use InvalidArgumentException;
use Exception;
use LogicException;
use Maghead\Runtime\Model;
use SQLBuilder\Raw;

class Param extends CascadingAttribute
{
    static public $typeClasses = [];


    /**
     * @var ActionKit\Action action object referenece
     * */
    protected $action;

    /**
     * @var string action param name
     */
    public $name;

    /**
     * @var string action param type
     */
    public $isa;

    /**
     * @var boolean is a required column ?
     *
     * XXX: note this is defined in supportedAttributes
     */
    public $required;

    /* current value ? */
    public $value;

    /* valid values */
    public $validValues;

    /* valid pair values */
    public $validPairs;

    public $optionValues;

    /* default value */
    public $default;

    /* is immutable ? */
    public $immutable;

    /* refer class *? */
    public $refer;

    /* default render Widget */
    public $widgetClass = 'TextInput';

    /* default widget attributes */
    public $widgetAttributes = array();

    /* default widget namespace */
    public $widgetNamespace = 'FormKit\\Widget';

    public $validator;

    protected $inflator;

    public function __construct($name, Action $action = null)
    {
        $this->name = $name;
        $this->action = $action;
        $this->build();
    }

    public function build()
    {

    }

    public static function getTypeClass($isa)
    {
        $isa = ucfirst($isa);
        if (!isset(self::$typeClasses[$isa])) {
            $isaClass = "ActionKit\\ValueType\\{$isa}Type";
            self::$typeClasses[$isa] = new $isaClass;
        }
        return self::$typeClasses[$isa];
    }

    public function isa($isa)
    {
        $isa = ucfirst($isa);
        // valid isa type
        if (!in_array($isa, ['Int', 'Num', 'Str', 'Bool', 'Dir', 'DateTime', 'Timestamp', 'Ip', 'Ipv4', 'Ipv6', 'Path', 'Regex', 'Url', 'Email'])) {
            throw new LogicException("Invalid isa '$isa' on param {$this->name}.");
        }
        $this->isa = $isa;
        return $this;
    }

    public function immutable()
    {
        $this->immutable = true;
        return $this;
    }

    public function required()
    {
        $this->required = true;
        return $this;
    }

    public function inflator(callable $inflator)
    {
        $this->inflator = $inflator;
        return $this;
    }

    public function inflate($value)
    {
        if ($this->inflator) {
            return call_user_func($this->inflator, $value, $this, $this->action);
        }

        // Built-in supported inflators
        if ($isa = $this->getAttributeValue('isa')) {
            switch ($isa) {
                case "DateTime":
                    if (is_int($value)) {
                        $dateTime = new DateTime();
                        $dateTime->setTimestamp($value);
                        return $dateTime;
                    } else if (is_string($value)) {
                        $dateTime = new DateTime($value);
                        return $dateTime;
                    } else {
                        throw new InvalidArgumentException("Invalid argument value for DateTime type param.");
                    }
                break;
                case "json":
                    if (is_string($value)) {
                        return json_decode($json);
                    } else {
                        throw new InvalidArgumentException("Invalid argument value for JSON type param.");
                    }
                break;
            }
        }

        return $value;
    }




    /**
     * typeCastValue method cast the form values to the corresponding php runtime value type.
     *
     * @param string|mixed $formValue usually string. (derived from $_POST, $_GET)
     */
    public function typeCastValue($formValue)
    {
        if ($isa = $this->getAttributeValue('isa')) {
            switch ($isa) {
            case 'int':
                return intval($formValue);
            case 'str':
                return (string) $formValue;
            case 'float':
                return floatval($formValue);
            case 'bool':
            case 'boolean':
                if ($formValue === NULL) {
                    return NULL;
                }
                if (is_string($formValue)) {
                    if ($formValue === '') {
                        return NULL;
                    } else {
                        return filter_var($formValue, FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE));
                    }
                }
            }
        }
        return $formValue;
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
    public function validate($value)
    {
        /* if it's file type , should read from $_FILES , not from the args of action */
        // TODO: note, we should do this validation in File Param or Image Param
        if ($this->action && $this->required) {
            if ($this->paramType === 'file') {
                if (! $this->action->request->file($this->name) && ! $this->action->request->param($this->name)) {
                    return [false, $this->action->messagePool->translate('file.required', $this->getLabel())];
                }
            } else {
                // We use '==' here because form values might be "" zero length string.
                if ($this->action->request->existsParam($this->name) && $this->action->request->param($this->name) == null && ! $this->default) {
                    return [false, $this->action->messagePool->translate('param.required', $this->getLabel())];
                }
            }

        }

        // isa should only work for non-null values.
        // empty string parameter is equal to null
        if ($this->isa && $this->action->request->existsParam($this->name))
        {
            if ($value !== '' && $value !== null) {
                $type = self::getTypeClass($this->isa);
                if (false === $type->test($value)) {
                    return [false, "Invalid type value on {$this->isa}"];
                }
            }
        }


        if ($this->validator) {
            return call_user_func($this->validator,$value);
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
        if ( $this->label ) {
            return _($this->label);
        }
        return ucfirst($this->name);
    }

    public function getDefaultValue()
    {
        if (is_callable($this->default)) {
            return call_user_func($this->default);
        }

        return $this->default;
    }


    public function getValidValues()
    {
        if ( is_callable($this->validValues) ) {
            return call_user_func($this->validValues);
        }
        return $this->validValues;
    }

    public function getOptionValues()
    {
        if ( is_callable($this->optionValues) ) {
            return call_user_func($this->optionValues);
        }
        return $this->optionValues;
    }



    /*******************************************************************************
     * Widget/UI Related Methods
     ******************************************************************************/

    /**
     * Render action column as {Type}Widget, with extra options/attributes
     *
     *     $this->column('created_on')
     *         ->renderAs('DateInput', array( 'format' => 'yy-mm-dd' ))
     *
     * @param string $type       Widget type
     * @param array  $attributes
     *
     * @return self
     */
    public function renderAs($type , array $attributes = null)
    {
        $this->widgetClass = $type;
        if ($attributes) {
            $this->widgetAttributes = array_merge( $this->widgetAttributes, $attributes );
        }

        return $this;
    }


    /**
     * Render current parameter column to HTML
     *
     * @param  array|null $attributes
     * @return string
     */
    public function render($attributes = null)
    {
        return $this->createWidget( null , $attributes )
            ->render();
    }

    public function createHintWidget($widgetClass = null , $attributes = array() )
    {
        if ($this->hint) {
            $class = $widgetClass ?: 'FormKit\\Element\\Div';
            $widget = new $class( $attributes );
            $widget->append($this->hint);

            return $widget;
        }
    }

    public function createLabelWidget($widgetClass = null , $attributes = array() )
    {
        $class = $widgetClass ?: 'FormKit\\Widget\\Label';
        if ($this->required) {
            return new $class('* ' . $this->getLabel());
        }
        return new $class($this->getLabel());
    }


    public function getRenderableCurrentValue()
    {
        // XXX: we should handle "false", "true", and "NULL"
        if ($this->value instanceof Model) {
            return $this->value->dataKeyValue();
        }
        if ($this->value instanceof Raw) {
            return null;
        }
        return $this->value;
    }

    /**
     * A simple widget factory for Action Param
     *
     * @param  string                    $widgetClass Widget Class.
     * @param  array                     $attributes  Widget attributes.
     * @return FormKit\Widget\BaseWidget
     */
    public function createWidget( $widgetClass = null , $attributes = array() )
    {
        $class = $widgetClass ?: $this->widgetClass;

        // convert attributes into widget style attributes
        $newAttributes = array();
        $newAttributes['label'] = $this->getLabel();

        if ($this->validValues) {
            $newAttributes['options'] = $this->getValidValues();
        } elseif ($this->optionValues) {
            $newAttributes['options'] = $this->getOptionValues();
        }

        if ($this->immutable) {
            $newAttributes['readonly'] = true;
        }

        // for inputs (except password input),
        // we should render the value (or default value)
        if (false === stripos($class , 'Password')) {
            // The Param class should respect the data type
            if ($this->value !== NULL) {
                if ($val = $this->getRenderableCurrentValue()) {
                    $newAttributes['value'] = $val;
                }
            } else if ($this->default) {
                $default = $this->getDefaultValue();
                if (!$default instanceof Raw) {
                    $newAttributes['value'] = $default;
                }
            }
        }

        if ($this->placeholder) {
            $newAttributes['placeholder'] = $this->placeholder;
        }
        if ($this->hint) {
            $newAttributes['hint'] = $this->hint;
        }

        if ($this->immutable) {
            $newAttributes['readonly'] = true;
        }

        // merge override attributes
        if ($this->widgetAttributes) {
            $newAttributes = array_merge($newAttributes , $this->widgetAttributes);
        }
        if ($attributes) {
            $newAttributes = array_merge($newAttributes , $attributes);
        }

        // if it's not a full-qualified class name
        // we should concat class name with default widget namespace
        if ('+' == $class[0]) {
            $class = substr($class,1);
        } else {
            $class = $this->widgetNamespace . '\\' . $class;
        }

        // create new widget object.
        return new $class($this->name , $newAttributes);
    }
}
