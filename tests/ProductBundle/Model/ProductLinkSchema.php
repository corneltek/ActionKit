<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaDeclare;

class ProductLinkSchema extends SchemaDeclare
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
