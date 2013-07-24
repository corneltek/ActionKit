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
            $ret = $record->delete();
        }
        $count = count($records);
        return $this->success( $count . '個項目刪除成功');
    }
}
