<?php
namespace ActionKit\RecordAction;

abstract class CreateRecordAction 
    extends BaseRecordAction
{
    const TYPE = 'create';

    public $enableLoadRecord = false;

    public function create($args)
    {
        $ret = $this->record->create( $args );

        /* error checking */
        if( false === $ret->success ) {
            $this->convertRecordValidation( $ret );
            if( function_exists('fb') ) {
                fb( $ret->message );
                fb( $ret->exception );
                fb( $ret->sql );
                fb( $ret->vars );
            }
            return $this->createError( $ret );
        }
        return $this->createSuccess( $ret );
    }


    public function processSubActions()
    {
        foreach( $this->relationships as $relationId => $relation ) {
            $recordClass = $relation['record'];
            $foreignKey = $relation['foreign_key'];
            $selfKey = $relation['self_key'];
            $argsList = $this->arg( $relationId );
            foreach( $argsList as $index => $args ) {
                // update related records with the main record id 
                // by using self_key and foreign_key
                $args[$selfKey] = $this->record->{$foreignKey};
                $files = array();
                if( isset($this->files[ $relationId ][ $index ]) ) {
                    $files = $this->files[$relationId][ $index ];
                }

                // run subaction
                $record = new $recordClass;
                if( isset($args['id']) && $args['id'] ) {
                    $record->load( $args['id'] );
                    $action = $record->asUpdateAction($args);
                } else {
                    unset($args['id']);
                    $action = $record->asCreateAction($args);
                }
                $action->files = $files;
                if( $action->invoke() === false ) {
                    $this->result = $action->result;
                    return false;
                }
            }
        }
    }

    /**
     * runValidate inherited from parent class.
     * */
    public function run()
    {
        /* default run method , to run create action */
        if( ! $this->create( $this->args ) )
            return;
        if( $this->nested && ! empty($this->relationships) )
            $this->processSubActions();
    }

    public function successMessage($ret)
    {
        return __("%1 Record is created." , $this->record->getLabel() );
    }

    public function errorMessage($ret)
    {
        // XXX: should show exception message when error is found.
        if($ret->exception)
            return __('Can not create %1 record: %2' , $this->record->getLabel(), $ret->exception->getMessage() );
        return __('Can not create %1 record.' , $this->record->getLabel() );
    }

    public function createSuccess($ret) 
    {
        return $this->success( $this->successMessage($ret) , array( 
            'id' => $this->record->id
        ));
    }

    public function createError($ret) 
    {
        return $this->error( $this->errorMessage($ret) );
    }

}


