<?php
namespace ActionKit\Param\Image;
use ImageKit\ImageProcessor;

class ScaleResize
{
    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function label()
    {
        return _('Scale');
    }

    public function resize($targetPath)
    {
        if (isset($this->param->size['height'])
            && isset($this->param->size['width']) )
        {
            $h = $this->param->size['height'];
            $w = $this->param->size['width'];

            $image = new ImageProcessor;
            $image->load( $targetPath );
            $image->resize($w, $h);

            // (filename, image type, jpeg compression, permissions);
            $image->save( $targetPath , null , $this->param->compression );
        }
    }
}
