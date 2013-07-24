<?php
namespace ActionKit\RecordAction;

class BulkCopyRecordAction extends BulkRecordAction
{
    const TYPE = 'bulk_copy';

    public $newFields = array('lang');
    public $unsetFields = array();

    public function run()
    {
        $newRecord = new $this->recordClass;
        $records = $this->loadRecords();

        foreach($records as $record) {
            $data = $record->getData();

            if ( $pk = $record->getSchema()->primaryKey ) {
                unset($data[$pk]);
            }

            if ( ! empty($this->unsetFields) ) {
                foreach( $this->unsetFields as $field ) {
                    unset($data[$field]);
                }
            }



            if ( ! empty($this->newFields) ) {
                foreach( $this->newFields as $field ) {
                    if ( $newValue = $this->arg($field) ) {
                        $data[$field] = $newValue;
                    } else {
                        unset($data[$field]);
                    }
                }
            }

            $ret = $newRecord->create($data);
            if ( ! $ret->success ) {
                return $this->error($ret->exception);
            }
        }
        return $this->success( count($records) . ' 個項目複製成功。');
    }

}


