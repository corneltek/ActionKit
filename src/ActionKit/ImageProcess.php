<?php
namespace ActionKit;

if( ! extension_loaded('gd') )
    throw new RuntimeException('gd extension is required.');
if( ! function_exists('imagecreatefromjpeg') )
    throw new RuntimeException('imagecreatefromjpeg function is required, your gd extension does not support jpeg?');
if( ! function_exists('imagecreatefrompng') )
    throw new RuntimeException('imagecreatefrompng function is required, your gd extension does not support png?');
if( ! function_exists('imagecreatefromgif') )
    throw new RuntimeException('imagecreatefromgif function is required, your gd extension does not support gif?');

class ImageException extends Exception {  }

class ImageProcess
{

    public $image;
    public $imageType;

    public function __construct($image = null) 
    {
        if ($image) {
            $this->image = $image;
        }
    }

    public function getImageInfo($filename)
    {
        return getimagesize($filename);
    }

    public function getImageType($filename)
    {
        $info = $this->getImageInfo($filename);
        return $info[2];
    }

    public function load($filename)
    {
        if( ! file_exists($filename) )
            throw new Exception("File $filename does not exist.");

        $this->imageType = $this->getImageType($filename);

        switch ($this->imageType) {

        case IMAGETYPE_JPEG:
            $this->image = imagecreatefromjpeg($filename);
            break;
        case IMAGETYPE_GIF:
            $this->image = imagecreatefromgif($filename);
            break;
        case IMAGETYPE_PNG:
            $this->image = imagecreatefrompng($filename);
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            break;
        default:
            throw new Exception("Unsupported image filetype.");
        }
        return $this->image;
    }

    public function save( $filename,
        $imageType = IMAGETYPE_JPEG,
        $compression = 100,
        $permissions = null )
    {
        if( ! is_writable($filename) ) {
            throw new ImageException("image file $filename is not writable.");
        }

        if( ! $imageType ) {
            $imageType = $this->imageType;
        }

        $ok = false;

        switch ($imageType) {
        case IMAGETYPE_JPEG:
            $ok = imagejpeg($this->image,$filename,$compression);
            break;
        case IMAGETYPE_GIF:
            $ok = imagegif($this->image,$filename);
            break;
        case IMAGETYPE_PNG:
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            $ok = imagepng($this->image,$filename);
            break;
        default:
            throw new ImageException("undefined image type");
        }

        if ($permissions != null) {
            chmod($filename,$permissions);
        }
        if ($ok === false) {
            throw new ImageException("Can not save image.");
        }
    }

    public function output($imageType=IMAGETYPE_JPEG)
    {
        if ($imageType == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);
        } elseif ( $imageType == IMAGETYPE_GIF ) {
            imagegif($this->image);
        } elseif ( $imageType == IMAGETYPE_PNG ) {
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            imagepng($this->image);
        }
    }


    /**
     * get image resource object
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * set image resource object
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getWidth()
    {
        return imagesx($this->image);
    }

    public function getHeight()
    {
        return imagesy($this->image);
    }

    public function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }




    public function resizeToWidth($width) 
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }


    /**
     * This scale method does not mean "resize scale",
     * which it actually does is scale to larger or scale to smaller with a 
     * factor.
     *
     * @param interger $scale
     */
    public function scale($scale)
    {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        return $this->resize($width,$height);
    }

    public function crop($left,$top,$width,$height) 
    {
        $new_image = $this->createNewImage($width,$height);
        imagecopy($new_image, $this->image, 
            // dst x,y
            0, 0,
            // src x,y
            $left,
            $top,
            $this->getWidth(),
            $this->getHeight()
        );
        return $this->image = $new_image;
    }

    public function cropOuterAndScale($new_width, $new_height, $outerPercentage = 10)
    {
        $current_width = $this->getWidth();
        $current_height = $this->getHeight();

        $d_width = $current_width - $new_width;
        $d_height = $current_height - $new_height;

        if( $d_height <= $d_width ) {
            // use $d_height
            $crop_height = $current_height * (100 - $outerPercentage) / 100;
            $ratio =  $crop_height / $new_height;
            $crop_width = $new_width * $ratio;
        } else {
            // use $d_width
            $crop_width = $current_width * (100 - $outerPercentage) / 100;
            $ratio =  $crop_width / $new_width;
            $crop_height = $new_height * $ratio;
        }
        $top = ($current_height - $crop_height) / 2;
        $left = ($current_width - $crop_width) / 2;
        $this->crop( $left, $top, $crop_width, $crop_height );
        // resize then
        $this->resize( $new_width , $new_height );
    }


    public function createNewImage($width,$height) 
    {
        $new_image = imagecreatetruecolor($width, $height);
        if($this->imageType == IMAGETYPE_PNG ) {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
        }
        return $new_image;
    }

    /**
     * Resize image to width and height
     *
     * @param integer $width
     * @param integer $height
     *
     * @return resized image
     */
    public function resize($width,$height)
    {
        $new_image = $this->createNewImage($width,$height);
        imagecopyresampled($new_image, $this->image, 
            0, 0, 
            0, 0, 
            $width, $height, 
            $this->getWidth(), 
            $this->getHeight());
        return $this->image = $new_image;
    }


    /**
     * Create new image object from specific area.
     *
     * @param integer $x
     * @param integer $y
     * @param integer $w
     * @param integer $h
     */
    public function copyFrom($x,$y,$w,$h)
    {
        $targetW = $w;
        $targetH = $h;
        $imageNew = $this->createNewImage($w,$h);
        if( imagecopyresampled($imageNew, $this->image,
            0, 0,
            $x, $y,
            $targetW, $targetH,
            $w, $h) === false
        ) {
            throw new Exception('imagecopyresampled failed.');
        }
        return $imageNew;
    }
}

