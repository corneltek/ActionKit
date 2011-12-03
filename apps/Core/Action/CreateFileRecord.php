<?php
# FIXME:

use Phifty\Action;
namespace Core\Action;

class CreateFileRecord extends Action 
{
    public $modelClass;
    public $fileColumns;

    public $limitSize;
    public $uploadPool;

    function getFiles() 
    {
        $list = array();
        foreach( $this->fileColumns as $name ) {
            array_push( $list , new UploadFile($name) );
        }
        return $list;
    }

    function renameFile( $file ) 
    {
        return $file->name;
    }

    function getFileDir( $file ) 
    {
        return $this->uploadPool;
    }

    /*
    Return relative file path (dictionary)
    */
    function moveFiles( $files ) 
    {
        $data = array();
        foreach( $files as $file ) {
            $filename = $this->renameFile( $file );
            $dir = $this->getFileDir( $file );
            $targetPath = $file->move_to_dir( 
                APPDIR . $dir , $filename );

            if( ! $targetPath )
                return false;

            $relatedPath = $dir . $filename;
            $data[ $file->column ] = $relatedPath;
        }
        return $data;
    }

    function afterMove( $files ) 
    {

    }

    function putFiles() 
    {

        $files = $this->getFiles();

        foreach( $files as $file ) {
            if( ! $file->found() )
                return $this->error( _("Please upload a file.") );
        }

        // check file error
        foreach( $files as $file ) {
            if( $file->has_error() ) 
                return $this->error( $file->error_message );
        }

        // check file size limit
        if( $this->limitSize ) {
            foreach( $files as $file )
                if( $file->get_size() > $this->limitSize )
                    return $this->error( _("Please upload a smaller file.") );
        }

        $dict = $this->moveFiles( $files );
        if( ! $dict )
            return $this->error( _("Upload failed.") );

        $this->afterMove( $files );

        // create record
        $args = $this->getData( $dict );
        return $this->createRecord( $args );
    }

    function run() 
    {
        return $this->putFiles();
    }

    function getData( $fileDict ) 
    {
        return $fileDict;
    }

    function createRecord( $args ) 
    {
        $cls = $this->modelClass;
        $m = new $cls();
        $ret = $m->create( $args );
        if( $m->id )
            return $this->success( _("Created.") );
        return $this->error( _("Create failed.") );
    }

}

?>
