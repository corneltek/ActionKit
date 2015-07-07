<?php
namespace ActionKit\Param;
use ActionKit\Param;
use Exception;
use LogicException;
use RuntimeException;
use ImageKit\ImageProcessor;
use ActionKit\RecordAction\UpdateRecordAction;
use ActionKit\RecordAction\CreateRecordAction;
use ActionKit\Utils;
use Universal\Http\UploadedFile;

function filename_increase($path)
{
    if (! file_exists($path)) {
        return $path;
    }

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

    public $argumentPostFilter;

    public function preinit(& $args)
    {
        // TODO: should let user create the directory by themself
        // futil_mkdir_if_not_exists(PH_APP_ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . $this->putIn, 0777, true);
    }

    public function build()
    {
        $this->supportedAttributes[ 'validExtensions' ] = self::ATTR_ARRAY;
        $this->supportedAttributes[ 'size' ] = self::ATTR_ARRAY;
        $this->supportedAttributes[ 'putIn' ] = self::ATTR_STRING;
        $this->supportedAttributes[ 'prefix' ] = self::ATTR_STRING;
        $this->supportedAttributes[ 'renameFile'] = self::ATTR_ANY;
        $this->supportedAttributes[ 'compression' ] = self::ATTR_ANY;
        $this->supportedAttributes[ 'argumentPostFilter' ] = self::ATTR_ANY;
        $this->renameFile = function($filename) {
            return filename_increase($filename);
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

    public function validate($value)
    {

        $ret = (array) parent::validate($value);
        if (false === $ret[0]) {
            return $ret;
        }

        $file = $this->action->request->file($this->name);

        // XXX: Consider required and optional situations.
        if (isset($_FILES[ $this->name ]['tmp_name']) && $_FILES[ $this->name ]['tmp_name'] )  {
            $file = new UploadFile( $this->name );
            if ( $this->validExtensions ) {
                if ( ! $file->validateExtension( $this->validExtensions ) ) {
                    return array( false, _('Invalid File Extension: ') . $this->name );
                }
            }

            if ( $this->sizeLimit ) {
                if ( ! $file->validateSize( $this->sizeLimit ) ) {
                    return array( false, _("The uploaded file exceeds the size limitation. ") . futil_prettysize($this->sizeLimit * 1024) );
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
            $this->hint .= '檔案大小限制: ' . futil_prettysize( $this->sizeLimit * 1024);
        }
        return $this;
    }

    public function hintFromSizeInfo($size = null)
    {
        if ($size) {
            $this->size = $size;
        }
        if ($this->sizeLimit) {
            $this->hint .= '<br/> 檔案大小限制: ' . futil_prettysize($this->sizeLimit*1024);
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

        // Upload from HTTP
        $upload = false;

        $file = $this->action->request->file($this->name);
        
        if ($file) {

            $upload = true;

        } else if ($this->action->arg($this->name)) {

            // If there is a file path specified in the form field instead of $_FILES
            // We will create a FILE array from the file system.
            $file = Utils::createFileArrayFromPath($this->action->arg($this->name));

        } else if ($this->sourceField) {
            // if there is no file found in $_FILES and $_REQUEST
            // we copy the file from another field's upload ($_FILES)

            $sourceFile = $this->action->request->file($this->sourceField);

            if ($sourceFile) {

                $file = $sourceFile;

            } else if ($arg = $this->action->arg($this->sourceField) ) {

                // If not, check another field's upload (either from POST or GET method)
                // Rebuild $_FILES arguments from file path (string) .
                $file = Utils::createFileArrayFromPath($arg);

            }
        }

        if ($file == null) {
            return;
        }

        $uploadedFile = UploadedFile::createFromArray($file);

        $targetPath = trim($this->putIn, DIRECTORY_SEPARATOR) 
            . DIRECTORY_SEPARATOR 
            . $uploadedFile->getOriginalFileName();

        if ($this->renameFile) {
            $targetPath = call_user_func($this->renameFile, $targetPath, $file);
        }

        while (file_exists($targetPath)) {
            $targetPath = filename_increase( $targetPath );
        }

        // If there is a file uploaded from HTTP
        if ($upload) {

            // The file array might be created from file system 
            if ($savedPath = $uploadedFile->getSavedPath()) {

                copy($savedPath, $targetPath);

            } else if ($uploadedFile->isUploadedFile()) {

                // move calls move_uploaded_file, which is only available for files uploaded from HTTP
                $uploadedFile->move($targetPath);

            } else {

                $uploadedFile->copy($targetPath);

            }

        } else if ($this->sourceField) { // If there is no http upload, copy the file from source field

            // source field only works for update record action
            // skip updating from source field if it's a update action
            if ($this->action instanceof UpdateRecordAction) {
                return;
            }

            if ($savedPath = $uploadedFile->getSavedPath()) {
                copy($savedPath, $targetPath);
            } else {
                $uploadedFile->copy($targetPath);
            }

        } else if (isset($file['saved_path']) && file_exists($file['saved_path']) ) {
            copy( $file['saved_path'], $targetPath);
        }


        // Update field path from target path
        //
        // argumentPostFilter is used for processing the value before inserting the data into database.
        if ($this->argumentPostFilter) {
            $a = call_user_func($this->argumentPostFilter, $targetPath);
            $args[$this->name]                 = $a;
            $this->action->args[ $this->name ] = $a; // for source field
        } else {
            $args[$this->name]                 = $targetPath;
            $this->action->args[ $this->name ] = $targetPath; // for source field
        }

        $this->action->addData($this->name , $targetPath);

        // if the auto-resize is specified from front-end
        if ( isset($args[$this->name . '_autoresize']) && $this->size ) {

            $t = @$args[$this->name . '_autoresize_type' ] ?: 'crop_and_scale';
            $process = ImageResizer::create($t, $this);
            $process->resize( $targetPath );

        } else {

            if ($rWidth = $this->resizeWidth) {
                $process = ImageResizer::create('max_width', $this);
                $process->resize($targetPath);
            } else if ( $rHeight = $this->resizeHeight ) {
                $process = ImageResizer::create('max_height', $this);
                $process->resize($targetPath);
            }

        }
    }
}
