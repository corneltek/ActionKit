<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaDeclare;

class ProductSubsectionSchema extends SchemaDeclare
{
    public function schema() 
    {
        $this->column('title')
            ->varchar(64)
            ->label('子區塊標題')
            ->renderAs('TextInput', [ 'size' => 50 ])
            ;

        $this->column('cover_image')
            ->varchar(64)
            ->label('子區塊封面圖')
            ->renderAs('ThumbImageFileInput')
            ;

        $this->column('content')
            ->text()
            ->label('子區塊內文')
            ->renderAs('TextareaInput', [
                'class' => '+=mceEditor',
                'rows'  => 5,
                'cols'  => 50,
            ])
            ;

        $this->column('product_id')
            ->integer()
            ->refer( 'ProductBundle\\Model\\Product')
            ;

        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
    }
}
