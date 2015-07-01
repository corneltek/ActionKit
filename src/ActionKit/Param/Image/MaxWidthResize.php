<?php
namespace ActionKit\Param\Image;
use ActionKit\ImageProcessor;

class MaxWidthResize
{
    public $param;


    public function __construct($param)
    {
        $this->param = $param;
    }

    public function label() {
        return _('Fit To Width');
    }

    public function resize($targetPath)
    {
        if ($this->param->resizeWidth) {
            $maxWidth = $this->param->resizeWidth;
        } else if (isset($this->param->size['width'])) {
            $maxWidth = $this->param->size['width'];
        }


        if ($maxWidth) {
            $image = new ImageProcessor;
            $image->load( $targetPath );

            // we should only resize image file only when size is changed.
            if ( $image->getWidth() > $maxWidth ) {
                $image->resizeToWidth($maxWidth);
                // (filename, image type, jpeg compression, permissions);
                $image->save( $targetPath , null , $this->param->compression );
            }
        }
    }
}
