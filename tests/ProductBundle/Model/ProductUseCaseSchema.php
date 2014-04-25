<?php
namespace ProductBundle\Model;
use LazyRecord\Schema;


class ProductUseCaseSchema extends Schema
{
    public function schema()
    {
        $this->column('product_id')
            ->integer()
            ->refer('ProductBundle\\Model\\ProductSchema')
            ->renderAs('SelectInput')
            ->label('產品')
            ;
        $this->column('usecase_id')
            ->integer()
            ->refer('UseCaseBundle\\Model\\UseCaseSchema')
            ->renderAs('SelectInput')
            ->label('關連案例')
            ;
        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
        $this->belongsTo('usecase','UseCaseBundle\\Model\\UseCaseSchema','id','usecase_id');
    }
}

}
