<?php
namespace ProductBundle\Model;
use LazyRecord\Schema;

class ProductProductSchema extends Schema
{
    public function schema() {
        $this->column('product_id')
            ->integer()
            ->refer('ProductBundle\\Model\\Product')
            ->renderAs('SelectInput')
            ->label('產品')
            ;
        $this->column('related_product_id')
            ->integer()
            ->refer('ProductBundle\\Model\\Product')
            ->renderAs('SelectInput')
            ->label('關連產品')
            ;


        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
        $this->belongsTo('related_product','ProductBundle\\Model\\ProductSchema','id','related_product_id');
    }
}


