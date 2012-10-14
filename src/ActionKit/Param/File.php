<?php
namespace ActionKit\Param;
use ActionKit\Param;
use Phifty\UploadFile;
use Phifty\FileUtils;
use Exception;

/**
 * Preprocess image data fields
 *
 * You can easily describe process and options for uploaded files
 *
 *    $this->param('file')
 *          ->putIn('path/to/pool')
 *          ->validExtension( array('png') )
 *          ->renameFile(function() {  })
 */
class File extends Param
{
    public $paramType = 'file';

    public $renameFile;
    public $sizeLimit;
    public $sourceField;  /* If field is not defined, use this source field */
    public $widgetClass = 'FileInput';

    public function build()
    {
        // XXX: use CascadingAttribute class setter instead.
        $this->supportedAttributes['validExtensions'] = self::ATTR_ARRAY;
        $this->supportedAttributes['putIn'] = self::ATTR_STRING;
        $this->supportedAttributes['sizeLimit'] = self::ATTR_ANY;
        $this->supportedAttributes['renameFile'] = self::ATTR_ANY;
    }

    public function preinit( & $args )
    {

        /* For safety , remove the POST, GET field !! should only keep $_FILES ! */
        if( isset( $args[ $this->name ] ) ) {
            // unset( $_GET[ $this->name ]  );
            // unset( $_POST[ $this->name ] );
            // unset( $args[ $this->name ]  );
        }
    }

    public function validate($value)
    {
        $ret = (array) parent::validate($value);
        if( $ret[0] == false )
            return $ret;

        // Consider required and optional situations.
        $file = $this->action->getFile($this->name);
        if( @$file['tmp_name'] )
        {
            $dir = $this->putIn;
            if( ! file_exists( $dir ) )
                return array( false , _("Directory $dir doesn't exist.") );

            $file = new UploadFile( $this->name );
            if( $this->validExtensions ) {
                if( ! $file->validateExtension( $this->validExtensions ) )
                    return array( false, __('Invalid File Extension: %1' . $this->name ) );
            }

            if( $this->sizeLimit )
                if( ! $file->validateSize( $this->sizeLimit ) )
                    return array( false, 
                        _("The uploaded file exceeds the size limitation. ") . $this->sizeLimit . ' KB.');
        }
        return true;
    }

    public function hintFromSizeLimit()
    {
        if( $this->sizeLimit ) {
            if( $this->hint )
                $this->hint .= '<br/>';
            else
                $this->hint = '';
            $this->hint .= '檔案大小限制: ' . FileUtils::pretty_size($this->sizeLimit*1024);
        }
        return $this;
    }

    public function init( & $args )
    {
        /* how do we make sure the file is a real http upload ?
         * if we pass args to model ? 
         *
         * if POST,GET file column key is set. remove it from ->args
         */
        if( ! $this->putIn )
            throw new Exception( "putIn attribute is not defined." );

        $req = new \Universal\Http\HttpRequest;
        $file = null;

        /* if the column is defined, then use the column 
         *
         * if not, check sourceField.
         * */
        if( isset($this->action->files[ $this->name ]) ) {
            $file = $this->action->files[ $this->name ];
        } elseif( $this->sourceField && isset($this->action->files[ $this->sourceField ]) ) {
            $file = $this->action->files[ $this->sourceField ];
        }

        if( $file && file_exists($file['tmp_name'] ) )
        {
            $newName = $file['name'];
            if( $this->renameFile ) {
                $newName = call_user_func($this->rename,$newName);
            }

            if( $this->putIn && ! file_exists($this->putIn) )
                mkdir( $this->putIn, 0755 , true );

            /* if we use sourceField, than use Copy */
            $savedPath = $this->putIn . DIRECTORY_SEPARATOR . $newName ;
            if( $this->sourceField ) {
                copy( $file['tmp_name'] , $savedPath);
            } else {
                move_uploaded_file( $file['tmp_name'], $savedPath);
            }
            $this->action->files[ $this->name ]['saved_path'] = $savedPath;
            $args[ $this->name ] = $savedPath;
            $this->action->addData( $this->name , $savedPath );
        }
    }

}


