<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\DeclareSchema;

class ProductFileSchema extends DeclareSchema
{
    public function getLabel()
    {
        return '產品檔案';
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
            ->label('檔案標題');

        $this->column( 'file' )
            ->varchar(130)
            ->required()
            ->label('檔案');
    }
}

