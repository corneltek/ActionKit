<?php
namespace ActionKit\Param\Image;

class CropAndScaleResize
{
    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function resize($targetPath)
    {
        if (isset($this->param->size['height'])
            && isset($this->param->size['width']) )
        {
            $h = $this->param->size['height'];
            $w = $this->param->size['width'];
            $image = $this->param->getImager();
            $image->load( $targetPath );
            $image->cropOuterAndScale($w,$h);
            $image->save( $targetPath , null , $this->param->compression );
        }
    }
}
