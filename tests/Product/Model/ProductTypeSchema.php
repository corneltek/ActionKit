<?php
namespace Product\Model;
use LazyRecord\Schema\SchemaDeclare;

class ProductTypeSchema extends SchemaDeclare
{
    public function schema()
    {
        $this->column('product_id')
            ->integer()
            ->label('Product')
            ->renderAs('SelectInput')
            ->refer('Product\\Model\\Product');

        $this->column('name')
            ->varchar(120)
            ->required()
            ->label('Name');
    }

    function dataLabel() {
        return $this->name;
    }
}
