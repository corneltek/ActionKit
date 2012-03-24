<?php

namespace ActionKit;
use ActionKit\RecordAction;
use Exception;

class DeleteRecordAction 
    extends RecordAction
{

    function delete($args)
    {
        $this->record->delete();
        return $this->deleteSuccess();
        // return $this->deleteError();
    }

    function run( ) 
    {
        /* default run method , to run create action */
        return $this->delete( $this->args );
    }

    function deleteSuccess() 
    {
        return $this->success( __('%1 record has been deleted.' , $this->record->getLabel() ) , array( 'id' => $this->record->id) );
    }

    function deleteError() 
    {
        return $this->error( __('Can not delete %1 record.' , $this->record->getLabel() ) );
    }



}


