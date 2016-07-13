<?php
namespace ActionKit;
use ActionKit\Param\Param;
use ActionKit\Action;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\RecordAction\CreateRecordAction;
use ActionKit\RecordAction\UpdateRecordAction;
use ActionKit\RecordAction\DeleteRecordAction;
use LazyRecord\BaseModel;
use LazyRecord\Schema\DeclareSchema;
use LazyRecord\Schema\SchemaInterface;
use LazyRecord\Schema\RuntimeColumn;
use SQLBuilder\Raw;
use Exception;

/**
 * Convert LazyORM column to Action param,
 * so that we can render with widget (currently).
 *
 * XXX: refactor this column converter
 */
class ColumnConvert
{
    /**
     * Convert a LazyRecord schema to action.
     *
     * This is used for generating an Action View without CRUD type.
     */
    public static function convertSchemaToAction(SchemaInterface $schema, BaseModel $record = null)
    {
        $columns = $schema->getColumns(true);
        $action = new BaseRecordAction(array(), $record);
        // no actual record is null
        $action->resetParams();
        $action->initParamsFromColumns($columns, $record);
        return $action;
    }


    /**
     * Translate LazyRecord RuntimeColumn to ActionKit param object.
     *
     * @param RuntimeColumn $column
     * @param BaseModel $record
     * @return Param;
     */
    public static function toParam(RuntimeColumn $column , BaseModel $record = null, Action $action = null)
    {
        $name = $column->name;
        $param = new Param($name, $action);
        if ($column->isa) {
            $param->isa($column->isa);
        }

        // Convert notNull to required
        // required() is basically the same as notNull but provides extra
        // software validation.
        // When creating records, the primary key with auto-increment support is not required.
        // However when updating records, the primary key is required for updating the existing record..
        if ($column->notNull) {
            if ($action && $column->primary) {
                if ($action instanceof CreateRecordAction) {

                    // autoIncrement is not defined, then it requires the input value.
                    if ($column->autoIncrement) {
                        $param->required = false;
                    } else {
                        $param->required = true;
                    }

                } else if ($action instanceof UpdateRecordAction || $action instanceof DeleteRecordAction) {
                    // primary key column is required to update/delete records.
                    $param->required = true;
                }
            } else {
                $param->required = true;
            }
        }

        foreach ($column->attributes as $k => $v) {
            // if the model column validator is not compatible with action validator
            if ($k === 'validator' || $v instanceof Raw) {
                continue;
            }
            $param->$k = $v;
        }

        // if we got record, load the value from it.
        if ($record) {
            // $val = $record->{$name};
            // $val = $val instanceof BaseModel ? $val->dataKeyValue() : $val;
            $val = $record->getValue($name);
            $param->value   = $val;

            // XXX: should get default value (from column definition)
            //      default value is only used in create action.
        } else {
            $default = $column->getDefaultValue();
            if (!$default instanceof Raw) {
                $param->value = $default;
            }
        }



        // convert related collection model to validValues
        if ($param->refer && ! $param->validValues) {
            if (class_exists($param->refer,true) ) {
                $referClass = $param->refer;

                // it's a `has many`-like relationship
                if ( is_subclass_of($referClass,'LazyRecord\\BaseCollection', true) ) {
                    $collection = new $referClass;
                    $options = array();
                    foreach ($collection as $item) {
                        $label = method_exists($item,'dataLabel')
                                ? $item->dataLabel()
                                : $item->id;
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $param->validValues = $options;
                } elseif ( is_subclass_of($referClass,'LazyRecord\\BaseModel', true) ) {
                    // it's a `belongs-to`-like relationship
                    $class = $referClass . 'Collection';
                    $collection = new $class;
                    $options = array();
                    foreach ($collection as $item) {
                        $label = method_exists($item,'dataLabel')
                                ? $item->dataLabel()
                                : $item->id;
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $param->validValues = $options;
                } elseif ( is_subclass_of($referClass, 'LazyRecord\\Schema\\DeclareSchema', true) ) {
                    $schema = new $referClass;
                    $collection = $schema->newCollection();

                    $options = array();
                    foreach ($collection as $item) {
                        $label = method_exists($item,'dataLabel')
                                ? $item->dataLabel()
                                : $item->id;
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $param->validValues = $options;
                } else {
                    throw new Exception('Unsupported refer type');
                }
            } elseif ( $relation = $record->getSchema()->getRelation($param->refer) ) {
                // so it's a relationship reference
                // TODO: implement this
                throw new Exception('Unsupported refer type');
            }
        }

        //  Convert column type to param type.
        // copy widget attributes
        if ($column->widgetClass) {
            $param->widgetClass = $column->widgetClass;
        }

        if ($column->widgetAttributes) {
            $param->widgetAttributes = $column->widgetAttributes;
        }

        if ( $column->immutable ) {
            $param->widgetAttributes['readonly'] = 'readonly';
        }

        if ($column->renderAs) {
            $param->renderAs( $column->renderAs );
        } elseif ($param->validValues || $param->validPairs || $param->optionValues) {
            $param->renderAs( 'SelectInput' );
        } elseif ($param->name === 'id') {
            $param->renderAs( 'HiddenInput' );
        } else {
            // guess input widget from data type
            $typeMapping = array(
                'date' => 'DateInput',
                'datetime' => 'DateTimeInput',
                'text' => 'TextareaInput',
            );
            if ( isset($typeMapping[ $param->type ]) ) {
                $param->renderAs($typeMapping[$param->type]);
            } else {
                $param->renderAs('TextInput');
            }
        }

        return $param;
    }
}