<?php

class UploadFileAction extends Action 
{
    var $filedir = "files/";

    function checkext($file_arg) {
        // skip ext check
        return true;

        // begin Dave B's Q&D file upload security code 
        $allowedExtensions = array("pdf","doc","rar","zip","gz","tar","ptt","html"); 
        if ($file_arg['tmp_name'] > '') { 
            if (!in_array(end(explode(".", 
                    strtolower($file_arg['name']))), 
                    $allowedExtensions)) { 
                return false;
            } 
        }
        return true;
    }

    function createRecord( $path , $title , $description , $size , $lang , $userid )
    {
        $sql = sprintf("INSERT INTO files ( "
                . " path , title , description , filesize , lang , created_by , created_on "
                . " ) VALUES ( '%s', '%s', '%s'  , %d , '%s' , %d , NOW() ); ", 
                        _e($path) , _e($title) , _e($description), $size , $lang , $userid ) ;
        $dbc = db_connect();
        $dbc->query( $sql );
        return $dbc->insert_id;
    }

	function run( )
	{
        $cuser = current_webuser();
        if( ! $cuser->id || $cuser->role != "admin" ) 
            return $this->report_error( _("Permission Denied.") );

		if( !@$_FILES['file'] || !@$_FILES['file']['tmp_name'] )
			return $this->report_error( _("File not found.") );

        $file_arg = $_FILES['file'];

        if( ! $this->checkext( $file_arg ) )
            return $this->report_error( _("Invalid file type.") );

        $path = $file_arg['tmp_name'];
        $newpath = $this->filedir . $file_arg['name'];

        $dbc = db_connect();
        if( ! copy( $path , $newpath ) ) {
            return $this->report_error( _("File upload failed.") );
        }


        $title = $this->get_arg( 'title' );
        $description = $this->get_arg( 'description' );
        $lang        = $this->get_arg( 'lang' );
        $size        = $_FILES['file']['size'];

        if( $lang == null ) {
            $lang = current_lang();
        }
        $record_id = $this->createRecord( $newpath , $title , $description , $size , $lang , $cuser->id );
        return $this->report_success( _('Upload success') , array( "id" => $record_id ) );

	}
}

?>
