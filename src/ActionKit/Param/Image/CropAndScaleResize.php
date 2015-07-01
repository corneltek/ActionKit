<?php
namespace ActionKit\Param\Image;
use ActionKit\ImageProcessor;

class CropAndScaleResize
{
    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function label()
    {
        return _('Crop Then Scale');
    }

    public function resize($targetPath)
    {
        if (isset($this->param->size['height'])
            && isset($this->param->size['width']) )
        {
            $h = intval($this->param->size['height']);
            $w = intval($this->param->size['width']);
            $image = new ImageProcessor;
            $image->load( $targetPath );

            $size = getimagesize($targetPath);
            if ( $size[0] > $w || $size[1] > $h ) {
                $image->cropOuterAndScale($w,$h);
            }
            $image->save( $targetPath , null , $this->param->compression );
        }
    }
}
