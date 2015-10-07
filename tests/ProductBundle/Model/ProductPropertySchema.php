<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\DeclareSchema;

class ProductPropertySchema extends DeclareSchema
{
    public function schema() 
    {
        $this->column('name')->varchar(64);
        $this->column('val')->varchar(512);
        $this->column('product_id')
            ->integer()
            ->refer( 'ProductBundle\\Model\\ProductSchema')
            ;
        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
    }
}
