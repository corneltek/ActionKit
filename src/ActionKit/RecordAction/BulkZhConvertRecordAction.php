<?php
namespace ActionKit\RecordAction;
use ActionKit\RecordAction\BulkRecordAction;

class BulkZhConvertRecordAction extends BulkRecordAction
{
    public $convertionKeys = array();
    public $convertionFunctions = array('to_cn','to_tw');

    public function convertRecords($convertion,$records)
    {
        foreach ($records as $record) {
            $args = $record->getData();
            $newArgs = array();
            foreach ($this->convertionKeys as $key) {
                if ( ! isset($args[$key]) )
                    continue;
                $newArgs[ $key ] = call_user_func( $convertion, $args[ $key ]);
            }
            $record->update($newArgs);
        }
    }

    public function run()
    {
        kernel()->library->load('han-convert');
        $convertion = $this->arg('convertion');
        if ( ! in_array($convertion,$this->convertionFunctions) )

            return $this->error('Invalid convertion method.');

        $records = $this->loadRecords();
        $this->convertRecords($convertion,$records);

        return $this->success(count($records) . '個項目轉換成功');
    }
}
