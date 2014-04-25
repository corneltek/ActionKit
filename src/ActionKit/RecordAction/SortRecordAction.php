<?php
namespace ActionKit\RecordAction;
use ActionKit\Action;
use Exception;

abstract class SortRecordAction extends Action
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

    public function loadRecord($id)
    {
        return new $this->recordClass( (int) $id);
    }

    public function runUpdateList() 
    {
        $orderingList = json_decode($this->arg('list'));
        if ( $this->mode == self::MODE_INCREMENTALLY ) {
            foreach( $orderingList as $ordering ) {
                $record = $this->loadRecord( (int) $ordering->record );
                $ret = $record->update(array( $this->targetColumn => $ordering->ordering ));
                if ( ! $ret->success ) {
                    throw new Exception($ret->message);
                }
            }
        }
    }

    public function run()
    {
        try {
            $this->runUpdateList();
        } catch ( Exception $e ) {
            return $this->error( __('Ordering Update Failed: %1', $e->getMessage() ) );
        }
        return $this->success('排列順序已更新');
    }
}


