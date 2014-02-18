<?php
namespace ActionKit\Param\Image;

class MaxHeightResize
{
    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function label()
    {
        return _('Fit To Height');
    }

    public function resize($targetPath)
    {
        if ($this->param->resizeHeight) {
            $maxHeight = $this->param->resizeHeight;
        } elseif (isset($this->param->size['height'])) {
            $maxHeight = $this->param->size['height'];
        }       


        if ($maxHeight) {
            $image = $this->param->getImager();
            $image->load( $targetPath );

            // we should only resize image file only when size is changed.
            if ( $image->getHeight() > $maxHeight ) {
                $image->resizeToHeight($maxHeight);
                // (filename, image type, jpeg compression, permissions);
                $image->save( $targetPath , null , $this->param->compression );
            }
        }
    }
}
