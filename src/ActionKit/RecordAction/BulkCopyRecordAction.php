<?php
namespace ActionKit\RecordAction;

class BulkCopyRecordAction extends BulkRecordAction
{
    const TYPE = 'bulk_copy';

    public $newFields = array('lang');
    public $unsetFields = array();

    public function schema()
    {
        foreach( $this->newFields as $field ) {
            $this->param($field);
        }
        parent::schema();
    }

    public function preprocessData($data) 
    {
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
        return $data;
    }

    public function unsetPrimaryKey($schema, $data)
    {
        if ( $pk = $schema->primaryKey ) {
            unset($data[$pk]);
        }
        return $data;
    }

    public function run()
    {
        $newRecord = new $this->recordClass;
        $schema = $newRecord->getSchema();

        $records = $this->loadRecords();
        foreach($records as $record) {
            $data = $record->getData();

            $data = $this->unsetPrimaryKey($record, $data);
            $data = $this->preprocessData($data);

            $ret = $newRecord->create($data);
            if ( ! $ret->success ) {
                return $this->error($ret->exception);
            }
        }
        return $this->success( count($records) . ' 個項目複製成功。');
    }

}


