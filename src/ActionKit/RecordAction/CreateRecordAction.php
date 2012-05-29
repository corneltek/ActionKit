<?php

namespace ActionKit\RecordAction;

class CreateRecordAction 
    extends BaseRecordAction
{
    function create($args)
    {
        $ret = $this->record->create( $args );

        /* error checking */
        if( false === $ret->success ) {
            $this->convertRecordValidation( $ret );
            return $this->createError( $ret );
        }
        return $this->createSuccess( $ret );
    }

    public function run()
    {
        /* default run method , to run create action */
        return $this->create( $this->args );
    }

    public function successMessage($ret)
    {
        return __("%1 Record is created." , $this->record->getLabel() );
    }

    public function errorMessage($ret)
    {
        return __('Can not create %1 record' , $this->record->getLabel() );
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


