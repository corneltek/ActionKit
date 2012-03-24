<?php

namespace Phifty\Action;
use Phifty\Action\RecordAction;

class CreateRecordAction 
    extends RecordAction
{
    function create($args)
    {
        $ret = $this->record->create( $args );

        /* error checking */
        if( false === $ret->success ) {
            $this->convertRecordValidation( $ret );

            var_dump( $ret->sql ); 
            var_dump( $ret ); 

            return $this->createError( $ret );
        }
        return $this->createSuccess( $ret );
    }

    function run()
    {
        /* default run method , to run create action */
        return $this->create( $this->args );
    }

    function createSuccess($ret) 
    {
        return $this->success( __("%1 Record has been created." , $this->record->getLabel() ) , array( 
            'id' => $this->record->id
        ));
    }

    function createError($ret) 
    {
        return $this->error( __('Can not create %1 record' , $this->record->getLabel() ) );
    }

}


