<?php
namespace ActionKit\RecordAction;

abstract class BulkDeleteRecordAction extends DeleteRecordAction
{
    const TYPE = 'bulk_delete';

    public $enableLoadRecord = false;

    public function runValidate() 
    {
        if( isset( $this->args['items'] ) )
            return false;  // no error
        return true;
    }

    public function run()
    {
        try {
            $items = $this->arg('items');
            $record = $this->record;
            foreach( $items as $id ) {
                $record->load( (int) $id );
                $ret = $record->delete();
            }
            return $this->deleteSuccess($ret);
        } catch( Exception $e ) {
            return $this->error( $e->getMessage() );
        }
    }
}

