<?php
namespace ActionKit;
use ActionKit\ActionGenerator;

class CRUD
{
    public static function generate($recordClass, $type)
    {
        $gen = new ActionGenerator(array( 'cache' => true ));
        $template = $gen->generateClassCode( $recordClass , $type );
        $className = $template->class->getFullName();

        // trigger spl classloader if needed.
        if ( class_exists($className ,true) ) {
            return $className;
        }
        $template->load();
        return $className;
    }
}
