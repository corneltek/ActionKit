<?php
namespace ActionKit\RecordAction;
use Exception;

abstract class DeleteRecordAction 
    extends BaseRecordAction
{

    public function run()
    {
        /* default run method , to run create action */
        return $this->doDelete( $this->args );
    }

    public function doDelete($args)
    {
        if( $this->record->delete()->success ) {
            return $this->deleteSuccess();
        } else {
            return $this->deleteError();
        }
    }


    /**
     * @inherit
     */
    public function runValidate()
    {
        if( isset( $this->args['id'] ) )
            return false;
        return true;
    }

    public function deleteSuccess() 
    {
        return $this->success( __('%1 record is deleted.' , $this->record->getLabel() ) , array( 'id' => $this->record->id) );
    }

    public function deleteError() 
    {
        return $this->error( __('Can not delete %1 record.' , $this->record->getLabel() ) );
    }



}


