<?php
namespace ActionKit\RecordAction;

abstract class CreateRecordAction
    extends BaseRecordAction
{
    const TYPE = 'create';

    public $enableLoadRecord = false;

    public function successMessage($ret)
    {
        return __("%1 Record is created." , $this->record->getLabel() );
    }

    public function errorMessage($ret)
    {
        // XXX: should show exception message when error is found.
        if ($ret->exception)

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
