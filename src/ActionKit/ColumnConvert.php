<?php
namespace ActionKit;
use ActionKit\Param;
use Exception;

/**
 * Convert LazyORM column to Action param, 
 * so that we can render with widget (currently).
 *
 * XXX: refactor this column converter
 */
class ColumnConvert 
{
    static function toParam( $column , $record = null )
    {
        $name = $column->name;
        $param = new Param( $name );
        foreach( $column->attributes as $k => $v ) {
            $param->$k = $v;
        }

        // if we got record, load the value from it.
        if( $record ) {
            $param->value   = $record->{$name};

            // XXX: should get default value (from column definition)
            //      default value is only used in create action.
            $param->default = $record->{$name};
        }

        if( $param->refer ) {
            if( class_exists($param->refer,true) ) {
                $class = $param->refer;

                // it's a `has many`-like relationship
                if( is_subclass_of($class,'LazyRecord\\BaseCollection', true) ) {
                    $collection = new $class;
                    $options = array();
                    foreach( $collection as $item ) {
                        $label = method_exists($item,'dataLabel') 
                                ? $item->dataLabel()
                                : $item->id;
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $param->validValues = $options;
                } 
                // it's a `belongs-to`-like relationship
                elseif( is_subclass_of($class,'LazyRecord\\BaseModel', true) ) {
                    $class = $class . 'Collection';
                    $collection = new $class;
                    $options = array();
                    foreach( $collection as $item ) {
                        $label = method_exists($item,'dataLabel') 
                                ? $item->dataLabel()
                                : $item->id;
                        $options[ $label ] = $item->dataKeyValue();
                    }
                    $param->validValues = $options;
                }
                else {
                    throw new Exception('Unsupported refer type');
                }
            }
            elseif( $relation = $record->getSchema()->getRelation($param->refer) ) {
                // so it's a relationship reference
                // TODO: implement this
                throw new Exception('Unsupported refer type');
            }
        }

        //  Convert column type to param type.
        // copy widget attributes
        if( $column->widgetClass ) {
            $param->widgetClass = $column->widgetClass;
        }
        if( $column->widgetAttributes ) {
            $param->widgetAttributes = $column->widgetAttributes;
        }

        if( $column->renderAs ) {
            $param->renderAs( $column->renderAs );
        }
        elseif( $param->validValues || $param->validPairs ) {
            $param->renderAs( 'SelectInput' );
        }
        elseif( $param->name === 'id' ) {
            $param->renderAs( 'HiddenInput' );
        } 
        else {
            // guess input widget from data type
            $typeMapping = array(
                'date' => 'DateInput',
                'datetime' => 'DateTimeInput',
                'text' => 'TextareaInput',
            );
            if( isset($typeMapping[ $param->type ]) ) {
                $param->renderAs($typeMapping[$param->type]);
            } else {
                $param->renderAs('TextInput');
            }
        }
        return $param;
    }
}



