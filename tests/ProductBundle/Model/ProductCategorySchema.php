<?php
namespace ProductBundle\Model;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\ProductTypeCollection;
use ProductBundle\Model\ProductImageCollection;
use ProductBundle\Model\ResourceCollection;
use Maghead\Schema\DeclareSchema;

class ProductCategorySchema extends DeclareSchema
{
    public function schema()
    {
        $this->table('product_category_junction');

        $this->column('product_id')
            ->integer()
            ->unsigned()
            ->required()
            ;
        $this->column('category_id')
            ->integer()
            ->unsigned()
            ->required()
            ;

        $this->belongsTo( 'category' , 'ProductBundle\\Model\\CategorySchema','id','category_id');
        $this->belongsTo( 'product' , 'ProductBundle\\Model\\ProductSchema','id','product_id');
    }
}


