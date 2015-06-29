<?php
namespace Product\Model;
use Product\Model\ProductCollection;
use Product\Model\ProductTypeCollection;
use Product\Model\ProductImageCollection;
use LazyRecord\Schema\SchemaDeclare;

class ProductSchema extends SchemaDeclare
{
    public function schema()
    {
        $this->column('name')
            ->varchar(256)
            ->label('Name')
            ->renderAs('TextInput',array( 'size' => 30 ))
            ;

        $this->column('category_id')
            ->refer('Product\Model\Category')
            ->integer()
            ->renderAs('SelectInput')
            ->label('產品類別');

        $this->column('cover_image')
            ->varchar(250)
            ->label('首頁封面圖')
            ->contentType('ImageFile')
            ->renderAs('ThumbImageFileInput')
            ;

        $this->column('ordering')
            ->integer()
            ->default(0)
            ->label('排序編號');

        $this->many('types',              'Product\Model\ProductTypeSchema' , 'product_id' , 'id' );  # to product id => image product_id
        $this->many('product_categories', 'Product\Model\ProductCategorySchema', 'product_id', 'id' );
        $this->manyToMany('categories',  'product_categories' , 'category');
    }
}


