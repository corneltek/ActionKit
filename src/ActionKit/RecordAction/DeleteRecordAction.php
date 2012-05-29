<?php
namespace ActionKit\RecordAction;
use Exception;

class DeleteRecordAction 
    extends BaseRecordAction
{

    function doDelete($args)
    {
        $ret = $this->record->delete();
        if( $ret->success )
            return $this->deleteSuccess();
        return $this->deleteError();
    }

    function run()
    {
        /* default run method , to run create action */
        return $this->doDelete( $this->args );
    }

    function runValidate()
    {
        if( isset( $this->args['id'] ) )
            return false;
        return true;
    }

    function deleteSuccess() 
    {
        return $this->success( __('%1 record is deleted.' , $this->record->getLabel() ) , array( 'id' => $this->record->id) );
    }

    function deleteError() 
    {
        return $this->error( __('Can not delete %1 record.' , $this->record->getLabel() ) );
    }



}


