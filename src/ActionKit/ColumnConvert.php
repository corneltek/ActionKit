<?php
namespace ActionKit;

/**
 * Convert LazyORM column to Action column, 
 * so that we can render with widget (currently).
 */
class ColumnConvert 
{

    static function toParam( $column , $record = null )
    {
        $name = $column->name;

        $param = new \ActionKit\Column( $name );

        foreach( $column->attributes as $k => $v ) {
            $param->$k = $v;
        }

        $param->name  = $name;

        if( $record ) {
            $param->value = $record->{$name};
        }

        /**
         * Convert column type to param type
         *
         * set default render widget
         */
        if( $param->validValues || $param->validPairs ) {
            $param->renderAs( 'Select' );
        }


        if( ! $param->widgetClass ) {
            $param->renderAs( 'Text' );
        } elseif( $param->renderAs ) {
            $param->widgetClass = '\Phifty\FormWidget\\' . $param->renderAs;
        }
        return $param;
    }


}



