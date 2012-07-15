<?php
namespace ActionKit;
use ActionKit\Param;

/**
 * Convert LazyORM column to Action param, 
 * so that we can render with widget (currently).
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
            // $param->value = $record->{$name};
            $param->default = $record->{$name};
        }

        // var_dump( $param->refer ); 

        //  Convert column type to param type.
        // copy widget attributes
        if( $column->widgetAttributes ) {
            $param->widgetAttributes = $column->widgetAttributes;
        }

        if( $column->validValues || $column->validPairs ) {
            $param->renderAs( 'SelectInput' );
        } elseif( $column->name === 'id' ) {
            $param->renderAs( 'HiddenInput' );
        } elseif( $column->renderAs ) {
            $param->renderAs( $column->renderAs );
        } else {
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



