<?php
namespace ActionKit\Param;
use ActionKit\Param;
use Phifty\UploadFile;
use Exception;
use SimpleImage;
use Phifty\FileUtils;


/**
 * Preprocess image data fields
 *
 * This preprocessor takes image file columns, 
 * copy these uploaded file to destination directory and 
 * update the original file hash, So in the run method of 
 * action class, user can simply take the hash arguments,
 * and no need to move files or validate size by themselfs.
 *
 * To define a Image Param column in Action schema:
 *
 *  
 *  public function schema() 
 *  {
 *     $this->param('image','Image')
 *          ->validExtensions('jpg','png');
 *  }
 *
 */
class Image extends Param
{

    // XXX: think about me.
    public $paramType = 'file';

    /* image column attributes */
    public $resizeWidth;
    public $resizeHeight;

    /**
     * @var array image size info, if this size info is specified, data-width, 
     * data-height will be rendered
     *
     * $size = array( 'height' => 200 , 'width' => 200 );
     *
     * is rendered as
     *
     * data-height=200 data-width=200
     *
     */
    public $size;

    public $validExtensions = array('jpg','jpeg','png','gif');

    public $compression = 99;

    /**
     * @var string relative path to webroot path.
     */
    public $putIn;

    public $sizeLimit;

    public $sourceField;  /* If field is not defined, use this source field */

    public $widgetClass = 'FileInput';

    public $renameFile;

    public function build()
    {
        $this->supportedAttributes[ 'validExtensions' ] = self::ATTR_ARRAY;
        $this->supportedAttributes[ 'size' ] = self::ATTR_ARRAY;
        $this->supportedAttributes[ 'putIn' ] = self::ATTR_STRING;
        $this->supportedAttributes[ 'prefix' ] = self::ATTR_STRING;
        $this->supportedAttributes[ 'renameFile'] = self::ATTR_ANY;
        $this->supportedAttributes[ 'compression' ] = self::ATTR_ANY;
        $this->renameFile = function($filename) {
            return FileUtils::filename_increase( $filename );
        };
        $this->renderAs('ThumbImageFileInput',array(
            /* prefix path for widget rendering */
            'prefix' => '/',
        ));
    }

    public function size( $size ) 
    {
        if ( $size ) {
            $this->widgetAttributes['dataWidth'] = $size['width'];
            $this->widgetAttributes['dataHeight'] = $size['height'];
            $this->widgetAttributes['autoresize'] = true;
            $this->widgetAttributes['autoresize_input'] = true;
            $this->widgetAttributes['autoresize_type_input'] = true;
            // default resize type
            // $this->widgetAttributes['autoresize_type'] = '';
        }
        return $this;
    }

    public function getImager()
    {
        kernel()->library->load('simpleimage');
        return new SimpleImage;
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
        if( @$_FILES[ $this->name ]['tmp_name'] )
        {
            $dir = $this->putIn;
            if( ! file_exists( $dir ) )
                return array( false , __("Directory %1 doesn't exist.",$dir) );

            $file = new UploadFile( $this->name );
            if( $this->validExtensions )
                if( ! $file->validateExtension( $this->validExtensions ) )
                    return array( false, _('Invalid File Extension: ') . $this->name );

            if( $this->sizeLimit )
                if( ! $file->validateSize( $this->sizeLimit ) )
                    return array( false, _("The uploaded file exceeds the size limitation. ") . $this->sizeLimit . ' KB.');
        }
        return true;
    }

    // XXX: should be inhertied from Param\File.
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

    public function hintFromSizeInfo($size = null)
    {
        if($size)
            $this->size = $size;

        if( $this->sizeLimit ) {
            $this->hint .= '<br/> 檔案大小限制: ' . FileUtils::pretty_size($this->sizeLimit*1024);
        }

        if( $this->size && isset($this->size['width']) && isset($this->size['height']) ) {
            $this->hint .= '<br/> 圖片大小: ' . $this->size['width'] . 'x' . $this->size['height'];
        }
        return $this;
    }

    public function init( & $args )
    {
        /* how do we make sure the file is a real http upload ?
         * if we pass args to model ? 
         *
         * if POST,GET file column key is set. remove it from ->args
         *
         * */
        if( ! $this->putIn )
            throw new Exception( "putIn attribute is not defined." );

        if( ! file_exists($this->putIn) )
            mkdir($this->putIn, 0755, true);

        $file = null;

        /* if the column is defined, then use the column 
         *
         * if not, check sourceField.
         * */
        if( isset($this->action->files[ $this->name ]) && $this->action->files[$this->name]['name'] ) {
            $file = $this->action->getFile($this->name);
        } 
        elseif ( $this->sourceField )
        {
            if( isset( $this->action->files[$this->sourceField] ) )
                $file = $this->action->getFile($this->sourceField);
            elseif ( isset( $this->action->args[$this->sourceField] ) ) {
                // rebuild $_FILES arguments from file path (string).
                $path = $this->action->args[$this->sourceField];
                $pathinfo = pathinfo($path);
                $file = array(
                    'name' => $pathinfo['basename'],
                    'tmp_name' => $path,
                    'saved_path' => $path,
                    'size' => filesize($path)
                );
            }
        }

        if( empty($file) || ! isset($file['name']) || !$file['name'] ) {
            // XXX: unset( $args[ $this->name ] );
            return;
        }

        $targetPath = $this->putIn . DIRECTORY_SEPARATOR . $file['name'];
        if( $this->renameFile ) {
            $targetPath = call_user_func($this->renameFile,$targetPath);
        }

        if( $this->sourceField ) {
            if( isset($file['saved_path']) ) {
                copy($file['saved_path'], $targetPath);
            }
            elseif( isset($file['tmp_name']) ) {
                copy($file['tmp_name'], $targetPath);
            } else {
                unset( $args[$this->name] );
                return;
            }
        } else {
            if( move_uploaded_file($file['tmp_name'],$targetPath) === false )
                die('File upload failed.');
        }

        $args[$this->name]  = $targetPath;
        $this->action->files[ $this->name ]['saved_path'] = $targetPath;
        $this->action->addData( $this->name , $targetPath );

        // resize image and save back.
        if( $this->resizeWidth ) {
            $image = $this->getImager();
            $image->load( $targetPath );

            // we should only resize image file only when size is changed.
            if( $image->getWidth() > $this->resizeWidth ) {
                $image->resizeToWidth( $this->resizeWidth );

                // (filename, image type, jpeg compression, permissions);
                $image->save( $targetPath , null , $this->compression );
            }
        }
    }
}

