<?php
namespace ActionKit\RecordAction;

abstract class BulkDeleteRecordAction extends DeleteRecordAction
{

    function runValidate() 
    {
        if( isset( $this->args['items'] ) )
            return false;  // no error
        return true;
    }

    function run()
    {
        try {
            $items = $this->arg('items');
            $record = $this->record;
            foreach( $items as $id ) {
                $record->load( (int) $id );
                $record->delete();
            }
            return $this->deleteSuccess();
        } catch( Exception $e ) {
            return $this->error( $e->getMessage() );
        }
    }
}

