<?php
namespace ActionKit\RecordAction;

use ActionKit\Action;

/**
 * This action requires 'items' param
 *
 *      {
 *          items: [ 1,2,3,4 ]
 *      }
 */
class BulkRecordAction extends Action
{
    public $recordClass;

    public function schema()
    {
        $this->param('items');
    }

    public function runValidate()
    {
        if (isset($this->args['items'])) {
            return true;  // no error
        }
        return false;
    }

    // TODO: we should use
    //    collection and where id in (1,2,3) to improve performance.
    public function loadRecords()
    {
        $itemIds = $this->arg('items');
        $records = array();
        foreach ($itemIds as $id) {
            $record = new $this->recordClass;
            $record->load((int) $id);
            if ($record->id) {
                $records[] = $record;
            }
        }
        return $records;
    }

    public function run()
    {
        $records = $this->loadRecords();
        foreach ($records as $record) {
            $ret = $record->delete();
        }

        return $this->deleteSuccess($ret);
    }
}
