<?php
namespace ActionKit\Param;
use ActionKit\Param\Param;
use ActionKit\Utils;
use Universal\Http\UploadedFile;
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
class FileParam extends Param
{
    public $paramType = 'file';

    public $sizeLimit;

    public $sourceField;  /* If field is not defined, use this source field */

    public $widgetClass = 'FileInput';

    public $renameFile;

    static $defaultUploadDirectory;

    public function build()
    {
        // XXX: use CascadingAttribute class setter instead.
        $this->supportedAttributes['validExtensions'] = self::ATTR_ARRAY;
        $this->supportedAttributes['putIn'] = self::ATTR_STRING;
        $this->supportedAttributes['sizeLimit'] = self::ATTR_ANY;
        $this->supportedAttributes['renameFile'] = self::ATTR_ANY;

        /*
        $this->renameFile = function($filename) {
            return Utils::filename_increase_suffix_number( $filename );
        };
         */
        if (static::$defaultUploadDirectory) {
            $this->putIn(static::$defaultUploadDirectory);
        }
    }

    public function validate($value)
    {
        $ret = (array) parent::validate($value);
        if ( $ret[0] == false )

            return $ret;

        // Consider required and optional situations.
        if ($fileArg = $this->action->request->file($this->name)) {
            $file = UploadedFile::createFromArray($fileArg);

            // If valid extensions are specified, pass to uploaded file to check the extension
            if ($this->validExtensions) {
                if ( ! $file->validateExtension($this->validExtensions) ) {
                    // return array(false, __('Invalid File Extension: %1' . $this->name ) );
                    return array(
                        false,
                        MessagePool::getInstance()->translate('Invalid File Extension: %1'),
                        $this->name 
                    );
                }
            }

            if ($this->sizeLimit) {
                if (! $file->validateSize( $this->sizeLimit )) {
                    return array(
                        false,
                        MessagePool::getInstance()->translate("The uploaded file exceeds the size limitation. %1 KB ", futil_prettysize($this->sizeLimit))
                    );
                }
            }
        }
        return true;
    }

    public function hintFromSizeLimit()
    {
        if ($this->sizeLimit) {
            if ($this->hint) {
                $this->hint .= '<br/>';
            } else {
                $this->hint = '';
            }
            $this->hint .= '檔案大小限制: ' . futil_prettysize($this->sizeLimit*1024);
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
        if (! $this->putIn) {
            throw new Exception( "putIn attribute is not defined." );
        }

        $file = null;
        $upload = false;

        /* if the column is defined, then use the column
         *
         * if not, check sourceField.
         * */

        if ($fileArg = $this->action->request->file($this->name)) {
            $upload = true;
            $file = $fileArg;
        } else if ($this->sourceField) {
            if ($fileArg = $this->action->request->file($this->sourceField)) {
                $file = $fileArg;
            }
        }
        if (!$file) {
            return false;
        }

        // TODO: Move this to a proper place
        if ($this->putIn && ! file_exists($this->putIn)) {
            mkdir($this->putIn, 0755 , true);
        }

        $uploadedFile = UploadedFile::createFromArray($file);

        $newName = $uploadedFile->getOriginalFileName();
        if ($this->renameFile) {
            $newName = call_user_func($this->rename,$newName);
        }
        $targetPath = $this->putIn . DIRECTORY_SEPARATOR . $newName ;


        // When sourceField enabled, we should either check saved_path or tmp_name
        if ($upload) {

            if ($savedPath = $uploadedFile->getSavedPath()) {

                $uploadedFile->move($targetPath);

            } else if ($uploadedFile->isUploadedFile()) {

                // move calls move_uploaded_file, which is only available for files uploaded from HTTP
                $uploadedFile->move($targetPath);

            } else {

                $uploadedFile->copy($targetPath);

            }


        } else if ($this->sourceField) {
            if ($savedPath = $uploadedFile->getSavedPath()) {
                copy($savedPath, $targetPath);
            } else {
                $uploadedFile->copy($targetPath);
            }
        } else {
            return;
        }

        $args[$this->name] = $targetPath;
        $this->action->addData( $this->name , $targetPath);
    }

}
