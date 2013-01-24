<?php
namespace ActionKit;

class CRUD
{
    public static function generate($modelClass, $types)
    {
        $runner = ActionRunner::getInstance();
        $nsList = explode('\\',ltrim($modelClass));
        if( count($nsList) > 1 ) {
            $prefix = $nsList[0];
            $modelName = end($nsList);

            foreach( (array) $types as $type ) {

            }

            $runner->registerCRUDAction();
        } else {

        }

        kernel()->action->registerCRUD( 
            $this->getNamespace(), 
            $model , 
            (array) $types 
        );
    }
}



