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
    public $size = array();

    public $validExtensions = array('jpg','jpeg','png','gif');

    public $compression = 99;

    /**
     * @var string relative path to webroot path.
     */
    public $putIn;

    /**
     * @var integer file size limit (default to 2048KB)
     */
    public $sizeLimit;

    public $sourceField;  /* If field is not defined, use this source field */

    public $widgetClass = 'FileInput';

    public $renameFile;

    public function preinit( & $args )
    {
        if (! $this->putIn) {
            throw new Exception( "putIn attribute is not defined." );
        }
        futil_mkdir_if_not_exists(PH_APP_ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . $this->putIn, 0755, true);
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
            return FileUtils::filename_increase( $filename );
        };
        $this->renderAs('ThumbImageFileInput',array(
            /* prefix path for widget rendering */
            'prefix' => '/',
        ));
        $this->putIn("static/upload/");
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
            if (isset($this->size['width'])) {
                $this->widgetAttributes['autoresize_types'][ _('Max Width') ] = 'max_width';
            }
            if (isset($this->size['height'])) {
                $this->widgetAttributes['autoresize_types'][ _('Max Height') ] = 'max_height';
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
        kernel()->library->load('simpleimage');

        return new SimpleImage;
    }


    public function validate($value)
    {
        $ret = (array) parent::validate($value);
        if ( false === $ret[0] ) {
            return $ret;
        }

        // Consider required and optional situations.
        if (@$_FILES[ $this->name ]['tmp_name']) {
            $file = new UploadFile( $this->name );
            if ( $this->validExtensions )
                if ( ! $file->validateExtension( $this->validExtensions ) )

                    return array( false, _('Invalid File Extension: ') . $this->name );

            if ( $this->sizeLimit )
                if ( ! $file->validateSize( $this->sizeLimit ) )

                    return array( false, _("The uploaded file exceeds the size limitation. ") . FileUtils::pretty_size($this->sizeLimit * 1024) );
        }

        return true;
    }

    // XXX: should be inhertied from Param\File.
    public function hintFromSizeLimit()
    {
        if ($this->sizeLimit) {
            if ( $this->hint )
                $this->hint .= '<br/>';
            else
                $this->hint = '';
            $this->hint .= '檔案大小限制: ' . FileUtils::pretty_size($this->sizeLimit*1024);
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

    public function init( & $args )
    {
        // constructing file
        $replacingRemote = false;
        $file = null;
        if ( isset($this->action->files[ $this->name ])
            && $this->action->files[$this->name]['name'] )
        {
            $file = $this->action->getFile($this->name);
        } elseif ( isset($this->action->args[$this->name]) ) {
            $file = FileUtils::fileobject_from_path(
                $this->action->args[$this->name]
            );
            $replacingRemote = true;
        } elseif ($this->sourceField) {
            if ( $this->action->hasFile($this->sourceField) ) {
                $file = $this->action->getFile($this->sourceField);
            }
            // check values from POST or GET path to string
            elseif ( isset( $this->action->args[$this->sourceField] ) ) {
                // rebuild $_FILES arguments from file path (string) .
                $file = FileUtils::fileobject_from_path(
                    $this->action->args[$this->sourceField]
                );
            }
        }

        if ( empty($file) || ! isset($file['name']) || !$file['name'] ) {
            // XXX: unset( $args[ $this->name ] );
            return;
        }

        $targetPath = trim($this->putIn,DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($file['name'], DIRECTORY_SEPARATOR);
        if ($this->renameFile) {
            $targetPath = call_user_func($this->renameFile,$targetPath);
        }

        if ($this->sourceField) {
            if ( isset($file['saved_path']) ) {
                copy($file['saved_path'], $targetPath);
            } elseif ( isset($file['tmp_name']) ) {
                copy($file['tmp_name'], $targetPath);
            } else {
                throw new Exception("source field not found.");

                return;
            }
        } else {
            // XXX: merge this
            if ($replacingRemote) {
                if ( isset($file['saved_path']) ) {
                    if ( $targetPath !== $file['saved_path'] )
                        copy($file['saved_path'], $targetPath);
                }
            } elseif ( move_uploaded_file($file['tmp_name'],$targetPath) === false ) {
                throw new Exception('File upload failed.');
            }
        }

        // update field path from target path
        $args[$this->name]  = $targetPath;
        $this->action->args[ $this->name ] = $targetPath; // for source field
        $this->action->files[ $this->name ]['saved_path'] = $targetPath;
        $this->action->addData( $this->name , $targetPath );

        if ( isset($args[$this->name . '_autoresize']) && $this->size ) {
            $t = @$args[$this->name . '_autoresize_type' ] ?: 'crop_and_scale';
            $classes = array(
                'max_width'      => 'ActionKit\Param\Image\MaxWidthResize',
                'max_height'     => 'ActionKit\Param\Image\MaxHeightResize',
                'scale'          => 'ActionKit\Param\Image\ScaleResize',
                'crop_and_scale' => 'ActionKit\Param\Image\CropAndScaleResize',
            );

            // echo "resizing {$this->name} with $t\n";
            // print_r( $this->size );

            if ( isset($classes[$t]) ) {
                $c = $classes[$t];
                $resizer = new $c($this);
                $resizer->resize( $targetPath );
            } else {
                throw new Exception("Unsupported autoresize_type $t");
            }
        }
    }
}
