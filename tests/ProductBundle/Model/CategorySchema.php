<?php
namespace ProductBundle\Model;
use ProductBundle\Model\ProductBundle;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\CategoryCollection;
use LazyRecord\Schema\SchemaDeclare;

class CategorySchema extends SchemaDeclare
{
    public function schema()
    {
        $this->table('product_categories');

        $this->column( 'name' )
            ->varchar(130)
            ->label('產品類別名稱')
            ->required(1);

        $this->column( 'description' )
            ->text()
            ->label('產品類別敘述')
            ->renderAs('TextareaInput',array(
                'class' => '+=mceEditor',
            ));

        $this->column( 'parent_id' )
            ->integer()
            ->refer( 'ProductBundle\\Model\\CategorySchema' )
            ->label( _('父類別') )
            ->integer()
            ->default(0)
            ->renderAs('SelectInput',array(
                'allow_empty' => 0,
            ));

        // hide this category in front-end
        $this->column('hide')
            ->boolean()
            ->label(_('隱藏這個類別'));

        $this->column('thumb')
            ->varchar(128)
            ->label('縮圖')
            ;

        $this->column('image')
            ->varchar(128)
            ->label('圖片');

        $this->column('handle')
            ->varchar(32)
            ->label(_('程式用操作碼'));


        $this->many('files','ProductBundle\\Model\\CategoryFile','category_id','id');
        $this->many('subcategories','ProductBundle\\Model\\CategorySchema','parent_id','id');
        $this->belongsTo('parent','ProductBundle\\Model\\CategorySchema','id','parent_id');

        $this->many( 'category_products', 'ProductBundle\\Model\\ProductCategorySchema', 'category_id', 'id' );
        $this->manyToMany( 'products',   'category_products' , 'product');
    }

    public function bootstrap($record)
    {
        $record->create(array('identity' => 'c1', 'name' => 'Category 1','lang' => 'en'));
        $record->create(array('identity' => 'c2', 'name' => 'Category 2','lang' => 'en'));
        $record->create(array('identity' => 'c3', 'name' => 'Category 3','lang' => 'en'));

        $record->create(array('name' => '類別 1', 'lang'     => 'zh_TW'));
        $record->create(array('name' => '類別 2', 'lang'     => 'zh_TW'));
        $record->create(array('name' => '類別 3', 'lang'     => 'zh_TW'));
    }
}



