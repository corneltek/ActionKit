<?php
namespace ActionKit\RecordAction;

abstract class DeleteRecordAction
    extends BaseRecordAction
{
    const TYPE = 'delete';

    public function run()
    {
        /* default run method , to run create action */

        return $this->doDelete( $this->args );
    }

    public function doDelete($args)
    {
        $ret = $this->record->delete();
        if ($ret->success) {
            return $this->deleteSuccess($ret);
        } else {
            return $this->deleteError($ret);
        }
    }

    /**
     * @inherit
     */
    public function runValidate()
    {
        if ( isset( $this->args['id'] ) ) {
            return true;
        }
        return false;
    }

    public function successMessage($ret)
    {
        return __('%1 record is deleted.' , $this->record->getLabel() );
    }

    public function errorMessage($ret)
    {
        return __('Can not delete %1 record.' , $this->record->getLabel() );
    }

    public function deleteSuccess($ret)
    {
        return $this->success($this->successMessage($ret), array( 'id' => $this->record->id) );
    }

    public function deleteError($ret)
    {
        return $this->error($this->errorMessage($ret));
    }

}
