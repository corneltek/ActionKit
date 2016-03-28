<?php
namespace ActionKit\Param;
use ActionKit\Param\Param;
use Exception;
use LogicException;
use RuntimeException;
use ImageKit\ImageProcessor;
use ActionKit\RecordAction\UpdateRecordAction;
use ActionKit\RecordAction\CreateRecordAction;
use ActionKit\Utils;
use Universal\Http\UploadedFile;


class ImageParam extends Param
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

    static $defaultUploadDirectory;

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
            return Utils::filename_increase_suffix_number($filename);
        };

        if (static::$defaultUploadDirectory) {
            $this->putIn(static::$defaultUploadDirectory);
        }

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

    /**
     * size method define the upload image dimension
     *
     * @param array $size
     */
    public function size($size)
    {
        $this->size = $size;
        $this->widgetAttributes['dataWidth'] = $size['width'];
        $this->widgetAttributes['dataHeight'] = $size['height'];
        return $this;
    }

    public function validate($value)
    {
        $ret = (array) parent::validate($value);
        if (false === $ret[0]) {
            return $ret;
        }

        $requireUploadMove = false;
        $uploadedFile = $this->_findUploadedFile($this->name, $requireUploadMove);
        if ($uploadedFile && $uploadedFile->hasError()) {
            return [false, $uploadedFile->getUserErrorMessage()];
        }

        $file = $this->action->request->file($this->name);
        if (!empty($file) && $file['name'] && $file['type']) {
            $uploadedFile = UploadedFile::createFromArray($file);
            if ($this->validExtensions) {
                if (! $uploadedFile->validateExtension($this->validExtensions) ) {
                    return [false, _('Invalid file extension: ') . $uploadedFile->getExtension()];
                }
            }
            if ($this->sizeLimit) {
                if ( ! $uploadedFile->validateSize( $this->sizeLimit ) ) {
                    return [false, _("The uploaded file exceeds the size limitation. ") . futil_prettysize($this->sizeLimit * 1024)];
                }
            }
        } else {
            if ($this->required) {
                return [false, "Field {$this->name} is required."];
            }
        }
        return true;
    }

    // XXX: should be inhertied from Param\File.
    public function hintFromSizeLimit()
    {
        if ($this->sizeLimit) {
            if ($this->hint) {
                $this->hint .= '<br/>';
            } else {
                $this->hint = '';
            }
            $this->hint .= '檔案大小限制: ' . futil_prettysize($this->sizeLimit * 1024);
        }
        return $this;
    }

    public function hintFromSizeInfo(array $size = null)
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
     * file uploading issue:
     *
     * When processing sub-actions, the file objects are shared across the
     * parent action. sometimes the column name will collide with the parent action column names.  
     * To solve this issue, we have to separate the namespace.
     *
     *   solutions:
     *     1. the file upload object should be action-wide not request-wide
     *     2. save the file upload object in the action, not in the request object.
     *     3. do not access $_FILES or request->files directly.
     *
     *   plan:
     *      1. Moved uploadedFile access methods from ActionRequest to Action itself.
     *
     */
    protected function _findUploadedFile($name, & $requireUploadMove)
    {
        // See if there is any UploadedFile object created in this action.
        $uploadedFile = $this->action->request->uploadedFile($name, 0);
        if ($uploadedFile) {
            return $uploadedFile;
        }

        // Uploaded by static path
        if ($uploadedPath = $this->action->arg($name)) {
            $fileArray = Utils::createFileArrayFromPath($uploadedPath);
            return UploadedFile::createFromArray($fileArray);
        }

        // create an uploaded file object from here
        $fileArray = $this->action->request->file($this->name);
        // if there is an upload file in $_FILES
        if ($fileArray && $fileArray['error'] == 0) {
            $requireUploadMove = true;
            return UploadedFile::createFromArray($fileArray);
        }
        return null;
    }

    /**
     * This init method move the uploaded file to the target directory.
     *
     * @param array $args request arguments ($_REQUEST)
     */
    public function init( & $args )
    {
        // Is the file upload from HTTP
        $requireUploadMove = false;
        $uploadedFile = $this->_findUploadedFile($this->name, $requireUploadMove);
        if (!$uploadedFile) {
            // Try to load uploadedFile from sourceField
            if ($this->sourceField) {
                $uploadedFile = $this->_findUploadedFile($this->sourceField, $requireUploadMove);
            }
        }

        if (!$uploadedFile) {
            return;
        }

        if ($uploadedFile->hasError()) {
            return;
        }

        $origFilename = $uploadedFile->getOriginalFileName();
        if (!$origFilename) {
            error_log('ImageParam: source field file not found');
            return;
        }

        $targetPath = trim($this->putIn, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $origFilename;
        if ($this->renameFile) {
            if ($ret = call_user_func($this->renameFile, $targetPath, $uploadedFile)) {
                $targetPath = $ret;
            }
        }

        // TODO: improve the file rename approach
        $renameLimit = 10;
        while ($targetPath && file_exists($targetPath) && $renameLimit--) {
            if ($a = Utils::filename_increase_suffix_number($targetPath)) {
                $targetPath = $a;
            }
        }


        // If there is a file uploaded from HTTP
        if ($requireUploadMove) {

            // The file array might be created from file system 
            if ($savedPath = $uploadedFile->getSavedPath()) {

                copy($savedPath, $targetPath);

            } else if ($uploadedFile->isUploadedFile()) {

                // move calls move_uploaded_file, which is only available for files uploaded from HTTP
                $uploadedFile->move($targetPath);

            } else {
                // is not an uploaded file?
                // may happen error here
                $uploadedFile->copy($targetPath);

            }

            $this->action->saveUploadedFile($this->name, 0, $uploadedFile);

        } else if ($this->sourceField) { // If there is no http upload, copy the file from source field

            // source field only works for update record action
            // skip updating from source field if it's a update action
            if ($this->action instanceof UpdateRecordAction) {
                return;
            }

            // Copy the file directly from the moved file path
            if ($savedPath = $uploadedFile->getSavedPath()) {

                copy($savedPath, $targetPath);

            } else {

                $uploadedFile->copy($targetPath);

            }

            $this->action->saveUploadedFile($this->name, 0, $uploadedFile);

        } else {

            return;

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

        // Don't resize gif files, gd doesn't support file resize with animation
        if ($uploadedFile->getExtension() != 'gif') {
            $this->autoResizeFile($targetPath);
        }
    }

    public function autoResizeFile($targetPath) 
    {

        // if the auto-resize is specified from front-end
        if ($this->action->request->param($this->name . '_autoresize') && $this->size ) {

            $t = $this->action->request->param($this->name . '_autoresize');
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
