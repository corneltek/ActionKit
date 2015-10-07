<?php
namespace ProductBundle\Model;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\ProductTypeCollection;
use ProductBundle\Model\ProductImageCollection;
use ProductBundle\Model\ResourceCollection;
use LazyRecord\Schema\DeclareSchema;

class ProductSchema extends DeclareSchema
{
    public function schema()
    {

        $this->column('name')
            ->varchar(256)
            ->label('產品名稱')
            ->renderAs('TextInput',array( 'size' => 30 ))
            ;

        $this->column('subtitle')
            ->varchar(256)
            ->label('產品副標題')
            ->renderAs('TextInput',array( 'size' => 60 ))
            ;

        $this->column('sn')
            ->varchar(128)
            ->label('產品序號');

        $this->column('description')
            ->text()
            ;

        $this->column('content')
            ->text()
            ->renderAs('TextareaInput');

        // always enable this
        $this->column('category_id')
            ->refer('ProductBundle\\Model\\CategorySchema')
            ->integer()
            ->label(_('產品類別'));

        /* is a cover product ? show this product in some specific pages? */
        $this->column('is_cover')
            ->boolean()
            ->renderAs('CheckboxInput')
            ->label(_('封面產品'));

        $this->column('sellable')
            ->boolean()
            ->renderAs('SelectInput')
            ->default(false)
            ->validValues([
                _('可販售') => 1,
                _('無法販售') => 0,
            ])
            ->label( _('可販售') )
            ->hint( _('選擇可販售之後，請記得新增產品類別，前台才可以加到購物車。') )
            ;

        $this->column('orig_price')
            ->integer()
            ->label('產品原價')
            ->renderAs('TextInput',[  'placeholder' => _('如: 3200') ])
            ;

        $this->column('price')
            ->integer()
            ->label('產品售價')
            ->renderAs('TextInput',[  'placeholder' => _('如: 2800') ])
            ;

        $this->column('external_link')
            ->varchar(256)
            ->label('外部連結')
            ->renderAs('TextInput',array( 'size' => 70, 'placeholder' => _('如: http://....') ))
            ;

        /* private token, for private customers */
        $this->column('token')
            ->varchar(128)
            ->label( _('秘密編號') )
            ->desc( _('使用者必須透過這組秘密編號的網址才能看到這個產品。') )
            ;

        $this->column('ordering')
            ->integer()
            ->default(0)
            ->label('排序編號');

        $this->column('hide')
            ->boolean()
            ->default(false)
            ->label(_('隱藏這個產品'))
            ->desc( _('目錄頁不要顯示這個產品，但是可以從網址列看到這個產品頁') );

        $this->many( 'product_features', 'ProductBundle\\Model\\ProductFeatureSchema', 'product_id', 'id' );
        $this->manyToMany( 'features',   'product_features' , 'feature' );


        $this->many( 'product_products', 'ProductBundle\\Model\\ProductProductSchema', 'product_id', 'id' );

        $this->manyToMany( 'related_products',   'product_products' , 'related_product' );


        $this->many('images',     'ProductBundle\\Model\\ProductImageSchema' , 'product_id' , 'id' );

        $this->many('properties',     'ProductBundle\\Model\\ProductPropertySchema' , 'product_id' , 'id' );

            ;  # to product id => image product_id
        $this->many('types',      'ProductBundle\\Model\\ProductTypeSchema' , 'product_id' , 'id' );

        $this->many('resources',  'ProductBundle\\Model\\ResourceSchema' , 'product_id' , 'id' );  # to product id => image product_id

        $this->many( 'subsections', 'ProductBundle\\Model\\ProductSubsectionSchema', 'product_id', 'id' )
            ->renderable(false);

        $this->many( 'links', 'ProductBundle\\Model\\ProductLinkSchema', 'product_id', 'id' )
            ->renderable(false);

        $this->many( 'product_categories', 'ProductBundle\\Model\\ProductCategorySchema', 'product_id', 'id' )
            ->renderable(false);
        $this->manyToMany( 'categories',   'product_categories' , 'category' )
            ->filter(function($collection) {
                return $collection;
            });
    }
}


