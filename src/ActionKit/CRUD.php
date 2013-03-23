<?php
namespace ActionKit;
use ActionKit\ActionGenerator;

class CRUD
{
    public static function generate($recordClass, $type)
    {
        $gen = new ActionGenerator(array( 'cache' => true ));
        $ret = $gen->generateClassCode( $recordClass , $type );

        // trigger spl classloader if needed.
        if ( class_exists($ret->action_class,true) ) {
            return $ret->action_class;
        }
        eval( $ret->code );
        return $ret->action_class;
    }
}
