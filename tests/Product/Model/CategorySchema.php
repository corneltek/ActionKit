<?php
namespace Product\Model;
use Product\Model\Product;
use Product\Model\ProductCollection;
use Product\Model\CategoryCollection;
use LazyRecord\Schema\SchemaDeclare;

class CategorySchema extends SchemaDeclare
{
    public function schema()
    {
        $this->table('product_categories');
        $this->column( 'name' )
            ->varchar(130)
            ->label('Category Name')
            ->required(1);
        $this->many( 'category_products', 'Product\\Model\\ProductCategorySchema', 'category_id', 'id' );
        $this->manyToMany( 'products',   'category_products' , 'product');
    }

    public function bootstrap($record)
    {
        $record->create(array('identity' => 'c1', 'name' => 'Category 1'));
        $record->create(array('identity' => 'c2', 'name' => 'Category 2'));
        $record->create(array('identity' => 'c3', 'name' => 'Category 3'));

        $record->create(array('name' => '類別 1'));
        $record->create(array('name' => '類別 2'));
        $record->create(array('name' => '類別 3'));
    }
}



