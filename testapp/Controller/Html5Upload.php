<?php

namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\Html5UploadHandler;

class Html5Upload extends \Phifty\Controller
{
    function run()
    {
        $handler = new Html5UploadHandler;
        // $handler->setUploadDir( webapp()->getWebRootDir() . DIR_SEP . 'static' . DIR_SEP . 'upload' );
        $handler->setUploadDir( 'static' . DIR_SEP . 'upload' );
        if( $handler->hasFile() )  {
            $ret = $handler->move();
            if( $ret )
                echo json_encode( array( 'success' => true,  'file' => $ret ) );
            else
                echo json_encode( array( 'error' => true , _('Upload failed.') ) );
            return;
        } else {
            echo json_encode(array( 'error' => _('Upload File not found.') ));
        }
    }
}

?>
