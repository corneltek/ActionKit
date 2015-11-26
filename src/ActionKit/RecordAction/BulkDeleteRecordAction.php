<?php
namespace ActionKit\RecordAction;

abstract class BulkDeleteRecordAction extends BulkRecordAction
{
    const TYPE = 'bulk_delete';


    /*
     * Define your record class here:
     *
     * public $recordClass;
     */


    public function run()
    {
        $records = $this->loadRecords();
        foreach ($records as $record) {
            $delete = $record->asDeleteAction();
            $delete->run();
        }
        $msg = $this->messagePool->translate('bulk_delete.successful_delete', count($records));
        return $this->success($msg);
    }
}
