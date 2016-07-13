<?php
namespace ActionKit\Param;
use ActionKit\Param\Param;
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

class ImageResizer
{
    static public $classes = array(
        'max_width'      => 'ActionKit\\Param\\Image\\MaxWidthResize',
        'max_height'     => 'ActionKit\\Param\\Image\\MaxHeightResize',
        'scale'          => 'ActionKit\\Param\\Image\\ScaleResize',
        'crop_and_scale' => 'ActionKit\\Param\\Image\\CropAndScaleResize',
    );

    static public function create($type, Param $param)
    {
        if (!isset(self::$classes[$type]) ) {
            throw new Exception("Image Resize Type '$type' is undefined.");
        }
        $c = self::$classes[$type];
        return new $c($param);
    }

}
