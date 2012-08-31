<?php
namespace ActionKit\Param;
use ActionKit\Param;
use Phifty\UploadFile;
use Exception;
use Phifty\SimpleImage;


/**
 * Preprocess image data fields
 */
class Image extends Param
{

    // XXX: think about me.
    public $paramType = 'file';

    /* image column attributes */
    public $resizeWidth;
    public $resizeHeight;

    public $validExtensions = array('jpg','jpeg','png','gif');
    public $putIn;
    public $renameFile;
    public $sizeLimit;
    public $sourceField;  /* If field is not defined, use this source field */
    public $widgetClass = 'FileInput';

    public function build()
    {
        $this->supportedAttributes[ 'validExtensions' ] = self::ATTR_ARRAY;
        $this->supportedAttributes[ 'putIn' ] = self::ATTR_STRING;
        $this->supportedAttributes[ 'prefix' ] = self::ATTR_STRING;
        $this->renderAs('ImageFileInput');
    }

    public function getImager()
    {
        return new SimpleImage;
    }

    public function getFile( $name )
    {
        return $_FILES[ $name ];
    }


    function preinit( & $args )
    {
        /* For safety , remove the POST, GET field !! should only keep $_FILES ! */
        if( isset( $args[ $this->name ] ) ) {
            unset( $_GET[ $this->name ]  );
            unset( $_POST[ $this->name ] );
            unset( $args[ $this->name ]  );
        }
    }

    function validate($value)
    {
        $ret = (array) parent::validate($value);
        if( $ret[0] == false )
            return $ret;

        // Consider required and optional situations.
        if( @$_FILES[ $this->name ]['tmp_name'] )
        {
            $dir = $this->putIn;
            if( ! file_exists( $dir ) )
                return array( false , _("Static dir $dir doesn't exist.") );

            $file = new UploadFile( $this->name );
            if( $this->validExtensions )
                if( ! $file->validateExtension( $this->validExtensions ) )
                    return array( false, _('Invalid File Extension: ' . $this->name ) );

            if( $this->sizeLimit )
                if( ! $file->validateSize( $this->sizeLimit ) )
                    return array( false, _("The uploaded file exceeds the size limitation. " . $this->sizeLimit . ' KB.'));
        }
        return true;
    }

    function init( & $args ) 
    {
        /* how do we make sure the file is a real http upload ?
         * if we pass args to model ? 
         *
         * if POST,GET file column key is set. remove it from ->args
         *
         * */
        // $file = $this->getFile( $this->name );
        if( ! $this->putIn )
            throw new Exception( "putIn attribute is not defined." );


        $file = null;


        /* if the column is defined, then use the column 
         *
         * if not, check sourceField.
         * */
        if( @$_FILES[ $this->name ]['name'] ) {
            $file = new UploadFile( $this->name );
        } else {
            if( $this->sourceField )
                $file = new UploadFile( $this->sourceField );
        }

        if( $file && $file->found() )
        {
            $newName = null;
            if( $this->renameFile ) {
                $cb = $this->renameFile;
                $newName = $cb( $file->name );
            }

            /* if we use sourceField, than use Copy */
            $file->putIn( $this->putIn , $newName , $this->sourceField ? true : false );

            $args[ $this->name ] = $this->prefixPath
                    ? $this->prefixPath . $file->getSavedPath()
                    : $file->getSavedPath()
                    ;
            $this->action->addData( $this->name , 
                $this->prefixPath
                    ? $this->prefixPath . $file->getSavedPath() 
                    : $file->getSavedPath()
            );

            // resize image and save back.
            if( $this->resizeWidth ) {
                $image = $this->getImager();
                $imageFile = $file->getSavedPath();
                $image->load( $imageFile );
                if( $image->getWidth() > $this->resizeWidth )
                    $image->resizeToWidth( $this->resizeWidth );
                $image->save( $imageFile );
            }

        }
    }


}


