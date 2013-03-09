<?php
namespace ActionKit\Param\Image;

class MaxWidthResize
{
    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function resize($targetPath)
    {
        if (isset($this->param->size['width'])) {
            $maxWidth = $this->param->size['width'];
        } elseif ($this->param->resizeWidth) {
            $maxWidth = $this->param->resizeWidth;
        }
        if ($maxWidth) {
            $image = $this->param->getImager();
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
