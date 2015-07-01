<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaDeclare;

class ProductImageSchema extends SchemaDeclare
{

    public function getLabel() {
        return '產品圖';
    }

    public function schema()
    {
        $this->column( 'product_id' )
            ->integer()
            ->refer('ProductBundle\\Model\\Product')
            ->renderAs('SelectInput')
            ->label('產品');

        $this->column( 'title' )
            ->varchar(130)
            ->label('圖片標題');

        $this->column('image')
            ->varchar(130)
            ->label('圖');

        $this->column('large')
            ->varchar(130)
            ->label('最大圖');

    }
}


