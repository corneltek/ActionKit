<?php
namespace ActionKit;

use ActionKit\Param\Param;
use ActionKit\Action;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\RecordAction\CreateRecordAction;
use ActionKit\RecordAction\UpdateRecordAction;
use ActionKit\RecordAction\DeleteRecordAction;
use Maghead\Runtime\Model;
use Maghead\Runtime\Collection;

use Maghead\Schema\DeclareSchema;
use Maghead\Schema\Schema;
use Maghead\Schema\RuntimeColumn;

use Magsql\Raw;
use Exception;

/**
 * Convert Maghead column to Action param,
 * so that we can render with widget (currently).
 */
class ColumnConvert
{
    public static $typeWidgets = [
        'date' => 'DateInput',
        'datetime' => 'DateTimeInput',
        'text' => 'TextareaInput',
    ];


    /**
     * Convert a Maghead schema to action.
     *
     * This is used for generating an Action View without CRUD type.
     */
    public static function convertSchemaToAction(Schema $schema, Model $record = null)
    {
        $columns = $schema->getColumns(true);
        $action = new BaseRecordAction(array(), $record);
        // no actual record is null
        $action->resetParams();
        $action->initParamsFromColumns($columns, $record);
        return $action;
    }


    protected static function setupDefault(Param $p, RuntimeColumn $c)
    {
        if ($c->default) {
            // do not use default value from the column if it's an instance of Raw
            if ($c->default instanceof Raw) {
                // let database put the default value by themself.
                // TODO: parse fixed value here.
            } else {
                $p->default($c->default);
            }
        }
    }

    protected static function setupRequired(Param $p, RuntimeColumn $c, BaseRecordAction $a = null)
    {
        // Convert notNull to required
        // required() is basically the same as notNull but provides extra
        // software validation.
        // When creating records, the primary key with auto-increment support is not required.
        // However when updating records, the primary key is required for updating the existing record..
        if ($c->notNull) {
            if ($a) {

                if ($c->primary) {

                    if ($a instanceof CreateRecordAction) {
                        // autoIncrement is not defined, then it requires the input value.
                        if ($c->autoIncrement) {
                            $p->required = false;
                        } else {
                            $p->required = true;
                        }
                    } else if ($a instanceof UpdateRecordAction || $a instanceof DeleteRecordAction) {
                        // primary key is required to update/delete records.
                        $p->required = true;
                    }
                } else {
                    $p->required = true;
                }

            } else {
                if (!$c->default && !($c->primary && $c->autoIncrement)) {
                    $p->required = true;
                }
            }
        }
    }

    protected static function setupIsa(Param $p, RuntimeColumn $c)
    {
        if ($c->isa) {
            $p->isa($c->isa);
        }
    }

    private static function setupCurrentValue(Param $p, RuntimeColumn $c, Model $r = null)
    {
        // if we got record, load the value from it.
        if ($r) {
            // $val = $r->{$name};
            // $val = $val instanceof Model ? $val->dataKeyValue() : $val;
            $val = $r->getValue($c->name);
            $p->value   = $val;

            // XXX: should get default value (from column definition)
            //      default value is only used in create action.
        } else {
            $default = $c->getDefaultValue();
            if (!$default instanceof Raw) {
                $p->value = $default;
            }
        }
    }


    private static function setupWidget(Param $p, RuntimeColumn $c)
    {
        if ($c->widgetClass) {
            $p->widgetClass = $c->widgetClass;
        }

        if ($c->widgetAttributes) {
            $p->widgetAttributes = $c->widgetAttributes;
        } else {
            $p->widgetAttributes = [];
        }

        if ($c->immutable) {
            $p->widgetAttributes['readonly'] = 'readonly';
        }
    }

    private static function setupWidgetType(Param $p, RuntimeColumn $c)
    {
        // When renderAs is specified, we should always use the widget name 
        if ($c->renderAs) {

            $p->renderAs($c->renderAs);

        } else {

            self::setupWidgetTypeAuto($p, $c);
            
        }
    }


    private static function setupWidgetTypeAuto(Param $p, RuntimeColumn $c)
    {
        if ($p->validValues || $p->validPairs || $p->optionValues) {

            $p->renderAs('SelectInput');

        } else if ($c->primary) {

            // When it's a primary key field, then it should be hidden input if renderAs is not specified.
            $p->renderAs('HiddenInput');

        } else {

            if (isset(static::$typeWidgets[ $p->type ])) {
                $p->renderAs(static::$typeWidgets[$p->type]);
            } else {
                $p->renderAs('TextInput');
            }
        }
    }


    private static function setupValidValues(Param $p, RuntimeColumn $c)
    {
        // convert related collection model to validValues
        if ($p->refer && ! $p->validValues) {
            if (class_exists($p->refer, true)) {
                $referClass = $p->refer;

                // it's a `has many`-like relationship
                if (is_subclass_of($referClass, Collection::class, true)) {
                    $collection = new $referClass;
                    $options = array();
                    foreach ($collection as $item) {
                        $label = method_exists($item, 'dataLabel')
                                ? $item->dataLabel()
                                : $item->getKey();
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $p->validValues = $options;
                } elseif (is_subclass_of($referClass, Model::class, true)) {
                    // it's a `belongs-to`-like relationship
                    $class = $referClass . 'Collection';
                    $collection = new $class;
                    $options = array();
                    foreach ($collection as $item) {
                        $label = method_exists($item, 'dataLabel')
                                ? $item->dataLabel()
                                : $item->getKey();
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $p->validValues = $options;
                } elseif (is_subclass_of($referClass, DeclareSchema::class, true)) {
                    $schema = new $referClass;
                    $collection = $schema->newCollection();

                    $options = array();
                    foreach ($collection as $item) {
                        $label = method_exists($item, 'dataLabel')
                                ? $item->dataLabel()
                                : $item->getKey();
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $p->validValues = $options;
                } else {
                    throw new Exception('Unsupported refer type');
                }
            } elseif ($relation = $record->getSchema()->getRelation($p->refer)) {
                // so it's a relationship reference
                // TODO: implement this
                throw new Exception('Unsupported refer type');
            }
        }

    }


    /**
     * Translate Maghead RuntimeColumn to ActionKit param object.
     *
     * @param RuntimeColumn $c
     * @param Model $record presents the current values
     * @return Param
     */
    public static function toParam(RuntimeColumn $c, Model $record = null, Action $action = null)
    {
        $name = $c->name;
        $p = new Param($name, $action);
        self::setupIsa($p, $c);
        self::setupDefault($p, $c);
        self::setupRequired($p, $c, $action);

        foreach ($c->attributes as $k => $v) {
            // skip some fields
            if (in_array(strtolower($k), ['validator', 'default'])) {
                continue;
            }
            if ($v instanceof Raw) {
                continue;
            }
            $p->$k = $v;
        }

        self::setupCurrentValue($p, $c, $record);
        self::setupValidValues($p, $c);
        self::setupWidget($p, $c);
        self::setupWidgetType($p, $c);
        return $p;
    }
}
