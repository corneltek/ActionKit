<?php
namespace ActionKit\RecordAction;

use ActionKit\Action;
use Exception;

abstract class UpdateOrderingRecordAction extends Action
{
    const MODE_INCREMENTALLY = 1;
    const MODE_BYDATE = 2;

    public $mode = self::MODE_INCREMENTALLY;

    /**
     * @var string the target model class.
     */
    public $recordClass;


    /**
     * @var string your model schema must provide the column for
     *             storing ordering data.
     */
    public $targetColumn = 'ordering';


    public function schema()
    {
        $this->param('list')->isa('str');
    }


    public function loadRecord($key)
    {
        return $this->recordClass::findByPrimaryKey($key);
    }

    public function runUpdateList()
    {
        if ($this->mode !== self::MODE_INCREMENTALLY) {
            throw new Exception("Unsupported sort mode");
        }
        if ($orderingList = json_decode($this->arg('list'))) {
            foreach ($orderingList as $ordering) {
                $record = $this->loadRecord($ordering->record);
                $ret = $record->update(array( $this->targetColumn => $ordering->ordering ));
                if ($ret->error) {
                    throw new Exception("Record update failed: {$ret->message}");
                }
            }
        }
    }

    public function run()
    {
        try {
            $this->runUpdateList();
        } catch (Exception $e) {
            return $this->error("Ordering Update Failed: {$e->getMessage()}");
        }
        return $this->success('排列順序已更新');
    }
}
