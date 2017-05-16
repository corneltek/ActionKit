<?php
namespace ProductBundle\Model;
use Maghead\Schema\DeclareSchema;

class ResourceSchema extends DeclareSchema
{
    public function schema()
    {
        $this->table('product_resources');
        $this->column('product_id')
            ->integer()
            ->refer('ProductBundle\\Model\\Product')
            ->label('產品')
            ;

        $this->column('url')
            ->varchar(256)
            ->label( '網址' )
            ;

        $this->column('html')
            ->varchar(512)
            ->label('內嵌 HTML')
            ->renderAs('TextareaInput')
            ;

        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
    }
}


