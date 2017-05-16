<?php
namespace ProductBundle\Model;
use Maghead\Schema\DeclareSchema;

class ProductLinkSchema extends DeclareSchema
{
    public function schema() 
    {
        $this->column('label')->varchar(128);
        $this->column('url')->varchar(128);
        $this->column('product_id')
            ->integer()
            ->refer( 'ProductBundle\\Model\\ProductSchema')
            ;
        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
    }
}
