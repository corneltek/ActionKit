<?php
namespace ProductBundle\Model;
use Maghead\Schema\DeclareSchema;

class ProductPropertySchema extends DeclareSchema
{
    public function schema() 
    {
        $this->column('name')->varchar(64);
        $this->column('val')->varchar(512);
        $this->column('product_id')
            ->integer()
            ->unsigned()
            ->refer(ProductSchema::class)
            ;
        $this->belongsTo('product', ProductSchema::class, 'id','product_id');
    }
}
