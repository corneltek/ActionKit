<?php
namespace Product\Model;
use Product\Model\ProductCollection;
use Product\Model\ProductTypeCollection;
use Product\Model\ProductImageCollection;
use Product\Model\ResourceCollection;
use LazyRecord\Schema\SchemaDeclare;

class ProductCategorySchema extends SchemaDeclare
{
    public function schema()
    {
        $this->table('product_category_junction');

        $this->column('product_id')
            ->integer();
        $this->column('category_id')
            ->integer();

        $this->belongsTo( 'category' , 'Product\Model\CategorySchema','id','category_id');
        $this->belongsTo( 'product' , 'Product\Model\ProductSchema','id','product_id');
    }
}


