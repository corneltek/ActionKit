<?php
namespace ProductBundle\Model;
use Phifty\FileUtils;

class ProductFile  extends \ProductBundle\Model\ProductFileBase 
{

    public function beforeUpdate($args)
    {
        if( isset($args['file']) )
            $args['mimetype'] = FileUtils::mimetype($args['file']);
        return $args;
    }

    public function beforeCreate($args)
    {
        if( isset($args['file']) )
            $args['mimetype'] = FileUtils::mimetype($args['file']);
        return $args;
    }

    public function updateMimetype()
    {
        if( file_exists($this->file) ) {
            $mimetype = FileUtils::mimetype($this->file);
            if($mimetype) {
                $this->update(array('mimetype' => $mimetype));
            }
        }
    }
}

