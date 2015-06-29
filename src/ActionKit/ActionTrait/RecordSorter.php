<?php
namespace ActionKit\ActionTrait;
use Kendo\Acl\MultiRoleInterface;

trait RecordSorter 
{
    public function loadRecord($id)
    {
        return new $this->recordClass( (int) $id);
    }

    public function runUpdateList() 
    {
        $orderingList = json_decode($this->arg('list'));
        if ( $this->mode == self::MODE_INCREMENTALLY ) {
            foreach ( $orderingList as $ordering ) {
                $record = $this->loadRecord( (int) $ordering->record );
                $ret = $record->update(array( $this->targetColumn => $ordering->ordering ));
                if ( ! $ret->success ) {
                    throw new Exception($ret->message);
                }
            }
        } else {
            throw new Exception("Unsupported sort mode");
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
