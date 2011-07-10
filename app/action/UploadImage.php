<?php

# MOVE THIS INTO FRAMEWORK
class UploadImageAction extends Action 
{
    var $base_path;
    var $image_dir = "images/";
    var $thumb_dir = "thumbs/";

    function setBasePath( $dir ) {
        $this->base_path = $dir;
    }

    function getBasePath() { return $this->base_path; }

    function setImageDir( $dir ) { 
        $this->image_dir = $dir;
    }
    function setThumbDir( $dir ) { 
        $this->thumb_dir = $dir;
    }

    function checkext($file_arg) {
        // begin Dave B's Q&D file upload security code 
        $allowedExtensions = array("png","jpg","jpeg"); 
        if ($file_arg['tmp_name'] > '') { 
            if (!in_array(end(explode(".", 
                    strtolower($file_arg['name']))), 
                    $allowedExtensions)) { 
                return false;
            } 
        }
        return true;
    }

    function createRecord( $thumb_path , $image_path , $userid , $type = "" )
    {
        $sql = sprintf("INSERT INTO images ( "
                . " thumb , path , type , created_by , created_on "
                . " ) VALUES ( '%s', '%s' , '%s', %d , NOW() ); ", 
                        $thumb_path , $image_path , $type , $userid ) ;
        $dbc = db_connect();
        $dbc->query( $sql );
        $image_id = $dbc->insert_id;
        return $image_id;
    }

	function run( )
	{
        $cuser = current_webuser();
        if( ! $cuser->id )
            return $this->report_error( _("You need to login to upload image.") );

        $this->setBasePath( APPDIR );
		$this->setImageDir( "images/upload/" );
		$this->setThumbDir( "images/upload/thumbs/" );

		if( !@$_FILES['image'] || !@$_FILES['image']['tmp_name'] )
			return $this->report_error( _("Image not found.") );

		return $this->process( $_FILES['image'] );
	}

    function process($file_arg)
    {
        if( ! $this->checkext( $file_arg ) )
            return $this->report_error( _("Invalid file type.") );

        $upd_image = $file_arg;
        $ext = strtolower(
            end(explode(".", $upd_image['name'])));
        $newfilename = MD5(time()) . "." . $ext;

        $image_path = $this->getBasePath() . $this->image_dir . $newfilename;
        $thumb_path = $this->getBasePath() . $this->thumb_dir . $newfilename;

        $image = new SimpleImage();

        $image->load($upd_image['tmp_name']);
        $image->save( $image_path );

        $image->load($upd_image['tmp_name']);

        if( $image->getWidth() > 250 )
            $image->resizeToWidth(250);

        $image->save( $thumb_path );

        if( ! file_exists( $thumb_path ) ) 
            return false;

        $type  = $this->get_arg( 'type' );
        $cuser = current_webuser();
        $image_id = $this->createRecord( 
            $this->thumb_dir . $newfilename , 
            $this->image_dir . $newfilename , 
            $cuser->id , $type );
        if( ! $image_id ) 
            return $this->error( _("Image upload error") );

        return $this->success( "Image uploaded." , array( 
                "id"    => $image_id,
                "thumb" => $this->thumb_dir . $newfilename ,
                "image" => $this->image_dir . $newfilename 
        ));
    }
}

?>
