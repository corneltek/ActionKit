<?php
namespace ActionKit\Param;
use ActionKit\Param;
use Phifty\UploadFile;
use Exception;
use RuntimeException;
use SimpleImage;
use Phifty\FileUtils;
use ActionKit\RecordAction\UpdateRecordAction;
use ActionKit\RecordAction\CreateRecordAction;

function filename_increase($path)
{
    if ( ! file_exists($path) )
        return $path;
    $pos = strrpos( $path , '.' );
    if ($pos !== false) {
        $filepath = substr($path, 0 , $pos);
        $extension = substr($path, $pos);
        $newfilepath = $filepath . $extension;
        $i = 1;
        while ( file_exists($newfilepath) ) {
            $newfilepath = $filepath . "_" . ($i++)  . $extension;
        }
        return $newfilepath;
    }

    return $path;
}

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

class ImageResizeProcess 
{
    static public $classes = array(
        'max_width'      => 'ActionKit\\Param\\Image\\MaxWidthResize',
        'max_height'     => 'ActionKit\\Param\\Image\\MaxHeightResize',
        'scale'          => 'ActionKit\\Param\\Image\\ScaleResize',
        'crop_and_scale' => 'ActionKit\\Param\\Image\\CropAndScaleResize',
    );

    static public function create($t, $param)
    {
        if ( isset(self::$classes[$t]) ) {
            $c = self::$classes[$t];
            return new $c($param);
        }
        return null;
    }


}


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
    public $size = array();

    public $validExtensions = array('jpg','jpeg','png','gif');

    public $compression = 99;

    /**
     * @var string relative path to webroot path.
     */
    public $putIn = 'upload';

    /**
     * @var integer file size limit (default to 2048KB)
     */
    public $sizeLimit;

    public $sourceField;  /* If field is not defined, use this source field */

    public $widgetClass = 'FileInput';

    public $renameFile;

    public function preinit( & $args )
    {
        futil_mkdir_if_not_exists(PH_APP_ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . $this->putIn, 0777, true);
    }

    public function build()
    {
        $this->supportedAttributes[ 'validExtensions' ] = self::ATTR_ARRAY;
        $this->supportedAttributes[ 'size' ] = self::ATTR_ARRAY;
        $this->supportedAttributes[ 'putIn' ] = self::ATTR_STRING;
        $this->supportedAttributes[ 'prefix' ] = self::ATTR_STRING;
        $this->supportedAttributes[ 'renameFile'] = self::ATTR_ANY;
        $this->supportedAttributes[ 'compression' ] = self::ATTR_ANY;
        $this->renameFile = function($filename) {
            return filename_increase( $filename );
        };
        $this->renderAs('ThumbImageFileInput',array(
            /* prefix path for widget rendering */
            'prefix' => '/',
        ));
    }

    public function autoResize($enable = true) 
    {
        if ($enable) {
            $this->enableAutoResize();
        } else {
            $this->disableAutoResize();
        }
        return $this;
    }

    public function disableAutoResize() 
    {
        $this->widgetAttributes['autoresize_input'] = false;
        $this->widgetAttributes['autoresize_input_check'] = false;
        $this->widgetAttributes['autoresize_type_input'] = false;
        return $this;
    }

    public function enableAutoResize()
    {
        // default autoresize options
        if ( ! empty($this->size) ) {
            $this->widgetAttributes['autoresize'] = true;
            $this->widgetAttributes['autoresize_input'] = true;
            $this->widgetAttributes['autoresize_input_check'] = true;
            $this->widgetAttributes['autoresize_type_input'] = true;
            $this->widgetAttributes['autoresize_types'] = array(
                _('Crop And Scale') => 'crop_and_scale',
                _('Scale') => 'scale',
            );
            if (isset($this->size['width']) || $this->resizeWidth) {
                $this->widgetAttributes['autoresize_types'][ _('Fit Width') ] = 'max_width';
            }
            if (isset($this->size['height']) || $this->resizeHeight) {
                $this->widgetAttributes['autoresize_types'][ _('Fit Height') ] = 'max_height';
            }
        }
        return $this;
    }

    public function size( $size )
    {
        if ( ! empty($size) ) {
            $this->size = $size;
            $this->widgetAttributes['dataWidth'] = $size['width'];
            $this->widgetAttributes['dataHeight'] = $size['height'];
        }
        return $this;
    }

    public function getImager()
    {
        // XXX: move this phifty-core dependency out.
        kernel()->library->load('simpleimage');
        return new SimpleImage;
    }


    public function validate($value)
    {
        $ret = (array) parent::validate($value);
        if ( false === $ret[0] ) {
            return $ret;
        }

        // XXX: Consider required and optional situations.
        if ( isset($_FILES[ $this->name ]['tmp_name']) && $_FILES[ $this->name ]['tmp_name'] )  {
            $file = new UploadFile( $this->name );
            if ( $this->validExtensions ) {
                if ( ! $file->validateExtension( $this->validExtensions ) ) {
                    return array( false, _('Invalid File Extension: ') . $this->name );
                }
            }

            if ( $this->sizeLimit ) {
                if ( ! $file->validateSize( $this->sizeLimit ) ) {
                    return array( false, _("The uploaded file exceeds the size limitation. ") . FileUtils::pretty_size($this->sizeLimit * 1024) );
                }
            }
        }
        return true;
    }

    // XXX: should be inhertied from Param\File.
    public function hintFromSizeLimit()
    {
        if ( $this->sizeLimit ) {
            if ( $this->hint ) {
                $this->hint .= '<br/>';
            } else {
                $this->hint = '';
            }
            $this->hint .= '檔案大小限制: ' . FileUtils::pretty_size( $this->sizeLimit * 1024);
        }
        return $this;
    }

    public function hintFromSizeInfo($size = null)
    {
        if ($size) {
            $this->size = $size;
        }
        if ($this->sizeLimit) {
            $this->hint .= '<br/> 檔案大小限制: ' . FileUtils::pretty_size($this->sizeLimit*1024);
        }
        if ( $this->size && isset($this->size['width']) && isset($this->size['height']) ) {
            $this->hint .= '<br/> 圖片大小: ' . $this->size['width'] . 'x' . $this->size['height'];
        }
        return $this;
    }


    /**
     * This init method move the uploaded file to the target directory.
     *
     * @param array $args request arguments ($_REQUEST)
     */
    public function init( & $args )
    {
        // constructing file
        $replace = false;
        $hasUpload = false;
        $file = null;

        // get file info from $_FILES, we have the accessor from the action class.
        if ( isset($this->action->files[ $this->name ]) && $this->action->files[$this->name]['name'] )
        {
            $file = $this->action->getFile($this->name);
            $hasUpload = true;
        }
        elseif ( isset($this->action->args[$this->name]) ) 
        {
            // If this input is a remote input file, which is a string sent from POST or GET method.
            $file = FileUtils::fileobject_from_path( $this->action->args[$this->name]);
            $replace = true;
        }
        elseif ($this->sourceField) 
        {
            // if there is no file found in $_FILES, we copy the file from another field's upload ($_FILES)
            if ( $this->action->hasFile($this->sourceField) ) 
            {
                $file = $this->action->getFile($this->sourceField);
            }
            elseif ( isset( $this->action->args[$this->sourceField] ) ) 
            {
                // If not, check another field's upload (either from POST or GET method)
                // Rebuild $_FILES arguments from file path (string) .
                $file = FileUtils::fileobject_from_path( $this->action->args[$this->sourceField] );
            }
        }


        // Still not found any file.
        if ( !$file || empty($file) || ! isset($file['name']) || !$file['name'] ) {
            // XXX: unset( $args[ $this->name ] );
            return;
        }

        // the default save path
        $targetPath = trim($this->putIn, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($file['name'], DIRECTORY_SEPARATOR);
        if ($this->renameFile) {
            $targetPath = call_user_func($this->renameFile, $targetPath);
        } else {
            $targetPath = filename_increase( $targetPath );
        }

        while (file_exists( $targetPath )) {
            $targetPath = filename_increase( $targetPath );
        }

        if ($hasUpload) {
            if ( isset($file['saved_path']) && file_exists($file['saved_path']) ) {
                copy( $file['saved_path'], $targetPath);
            } else {
                if ( move_uploaded_file($file['tmp_name'], $targetPath) === false ) {
                    throw new RuntimeException('File upload failed, Can not move uploaded file.');
                }
                $file['saved_path'] = $targetPath;
            } 
        } elseif ( $this->sourceField ) {
            // Skip updating from source field if it's a update action
            if ($this->action instanceof UpdateRecordAction) {
                return;
            }


            // Upload not found, so we decide to copy one from our source field file.
            if ( isset($file['saved_path']) && file_exists($file['saved_path']) )
            {
                copy($file['saved_path'], $targetPath);
            }
            elseif ( isset($file['tmp_name']) && file_exists($file['tmp_name']) ) 
            {
                copy( $file['tmp_name'], $targetPath);
            }
            else 
            {
                // Upload not found and source field is also empty.
                echo "Action: " , get_class($this->action) , "\n";
                echo "Current Field: " , $this->name , "\n";
                echo "File:\n";
                print_r($file);
                echo "Files:\n";
                print_r($this->action->files);
                echo "Args:\n";
                print_r($this->action->args);
                throw new RuntimeException('Can not copy image from source field, unknown error: ' 
                    . join(', ', array( get_class($this->action), $this->name))
                );
            }
        } elseif ( isset($file['saved_path']) && file_exists($file['saved_path']) ) {
            copy( $file['saved_path'], $targetPath);
        }


        // update field path from target path
        $args[$this->name]                 = $targetPath;
        $this->action->args[ $this->name ] = $targetPath; // for source field
        $this->action->files[ $this->name ]['saved_path'] = $targetPath;

        $this->action->addData( $this->name , $targetPath );

        // if the auto-resize is specified from front-end
        if ( isset($args[$this->name . '_autoresize']) && $this->size ) {
            $t = @$args[$this->name . '_autoresize_type' ] ?: 'crop_and_scale';
            if ( $process = ImageResizeProcess::create($t, $this) ) {
                $process->resize( $targetPath );
            } else {
                throw new RuntimeException("Unsupported autoresize_type $t");
            }
        } else {
            if ( $rWidth = $this->resizeWidth ) {
                if ( $process = ImageResizeProcess::create('max_width', $this) ) {
                    $process->resize($targetPath);
                }
            } else if ( $rHeight = $this->resizeHeight ) {
                if ( $process = ImageResizeProcess::create('max_height', $this) ) {
                    $process->resize($targetPath);
                }
            }
        }
    }
}
