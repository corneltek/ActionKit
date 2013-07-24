<?php
namespace ActionKit\RecordAction;

class BulkCopyRecordAction extends BulkRecordAction
{
    const TYPE = 'bulk_copy';

    public $newFields = array('lang');
    public $copyFields = array();

    public function run()
    {
        $newRecord = new $this->recordClass;
        $records = $this->loadRecords();

        foreach($records as $record) {
            $args = $record->getData();

            if ( $pk = $record->getSchema()->primaryKey ) {
                unset($args[$pk]);
            }

            foreach( $this->newFields as $field ) {
                if ( $newValue = $this->arg([$field]) ) {
                    $args[$field] = $newValue;
                } else {
                    unset($args[$field]);
                }
            }

            $ret = $newRecord->create($args);
            if ( ! $ret->success ) {
                return $this->error($ret->exception);
            }
        }
        return $this->success( count($records) . ' 個項目複製成功。');
    }

}


