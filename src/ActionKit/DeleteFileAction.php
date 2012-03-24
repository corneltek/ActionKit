<?php

namespace Phifty\Action;
use Phifty\Action;

/*
DeleteFileAction (abstract class)

Depends on record, and base path, then delete record.

Generate a file delete action( takes model, record id, callback);


Usage:

    SlideImage Model has file column.

    class DeleteSlideImageAction extends DeleteFileAction 
    {
        public $modelClass = "SlideImage";
        public function getModelFiles( $record ) {
            return array( APPDIR . $record->file );
        }
    }

*/
abstract class DeleteFileAction extends Action 
{

    public $modelClass;

    abstract public function getModelFiles( $record );

    public function deleteRecord($id) {
        $cls = $this->modelClass;
        if( ! $cls )
            throw new Exception( "Model Class undefined." );

        $record = new $cls( (int) $id );
        $files = $this->getModelFiles( $record );
        foreach( $files as $file ) {
            if( file_exists( $file ) )
                unlink( $file );
        }
        $record->delete($id);
    }

    function run() {
        $id = $this->arg("id");
        if( ! $id )
            return $this->error( _("Require ID") );
        $this->deleteRecord( $id );
        return $this->success( _("Deleted.") );
    }

}


?>
