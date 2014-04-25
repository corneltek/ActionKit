<?php
namespace ProductBundle\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'name',
  1 => 'subtitle',
  2 => 'sn',
  3 => 'description',
  4 => 'content',
  5 => 'category_id',
  6 => 'is_cover',
  7 => 'sellable',
  8 => 'orig_price',
  9 => 'price',
  10 => 'external_link',
  11 => 'token',
  12 => 'hide',
  13 => 'id',
);
    public static $column_hash = array (
  'name' => 1,
  'subtitle' => 1,
  'sn' => 1,
  'description' => 1,
  'content' => 1,
  'category_id' => 1,
  'is_cover' => 1,
  'sellable' => 1,
  'orig_price' => 1,
  'price' => 1,
  'external_link' => 1,
  'token' => 1,
  'hide' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'name',
  1 => 'subtitle',
  2 => 'sn',
  3 => 'description',
  4 => 'content',
  5 => 'category_id',
  6 => 'is_cover',
  7 => 'sellable',
  8 => 'orig_price',
  9 => 'price',
  10 => 'external_link',
  11 => 'token',
  12 => 'hide',
  13 => 'id',
);

    const schema_class = 'ProductBundle\\Model\\ProductSchema';
    const collection_class = 'ProductBundle\\Model\\ProductCollection';
    const model_class = 'ProductBundle\\Model\\Product';
    const model_name = 'Product';
    const model_namespace = 'ProductBundle\\Model';
    const primary_key = 'id';
    const table = 'products';
    const label = 'Product';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(256)',
          'isa' => 'str',
          'size' => 256,
          'label' => '產品名稱',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 30,
            ),
        ),
    ),
  'subtitle' => array( 
      'name' => 'subtitle',
      'attributes' => array( 
          'type' => 'varchar(256)',
          'isa' => 'str',
          'size' => 256,
          'label' => '產品副標題',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 60,
            ),
        ),
    ),
  'sn' => array( 
      'name' => 'sn',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
          'label' => '產品序號',
        ),
    ),
  'description' => array( 
      'name' => 'description',
      'attributes' => array( 
          'type' => 'text',
          'isa' => 'str',
        ),
    ),
  'content' => array( 
      'name' => 'content',
      'attributes' => array( 
          'type' => 'text',
          'isa' => 'str',
          'renderAs' => 'TextareaInput',
          'widgetAttributes' => array( 
            ),
        ),
    ),
  'category_id' => array( 
      'name' => 'category_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'refer' => 'ProductBundle\\Model\\CategorySchema',
          'renderAs' => '+CRUD\\Widget\\QuickCRUDSelectInput',
          'widgetAttributes' => array( 
              'record_class' => 'ProductBundle\\Model\\Category',
              'dialog_path' => '/bs/product_category/crud/quick_create',
              'allow_empty' => true,
            ),
          'label' => '產品類別',
        ),
    ),
  'is_cover' => array( 
      'name' => 'is_cover',
      'attributes' => array( 
          'type' => 'boolean',
          'isa' => 'bool',
          'renderAs' => 'CheckboxInput',
          'widgetAttributes' => array( 
            ),
          'label' => '封面產品',
        ),
    ),
  'sellable' => array( 
      'name' => 'sellable',
      'attributes' => array( 
          'type' => 'boolean',
          'isa' => 'bool',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'default' => false,
          'validValues' => array( 
              '可販售' => 1,
              '無法販售' => 0,
            ),
          'label' => '可販售',
          'hint' => '選擇可販售之後，請記得新增產品類別，前台才可以加到購物車。',
        ),
    ),
  'orig_price' => array( 
      'name' => 'orig_price',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'label' => '產品原價',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'placeholder' => '如: 3200',
            ),
        ),
    ),
  'price' => array( 
      'name' => 'price',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'label' => '產品售價',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'placeholder' => '如: 2800',
            ),
        ),
    ),
  'external_link' => array( 
      'name' => 'external_link',
      'attributes' => array( 
          'type' => 'varchar(256)',
          'isa' => 'str',
          'size' => 256,
          'label' => '外部連結',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 70,
              'placeholder' => '如: http://....',
            ),
        ),
    ),
  'token' => array( 
      'name' => 'token',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
          'label' => '秘密編號',
          'desc' => '使用者必須透過這組秘密編號的網址才能看到這個產品。',
        ),
    ),
  'hide' => array( 
      'name' => 'hide',
      'attributes' => array( 
          'type' => 'boolean',
          'isa' => 'bool',
          'default' => false,
          'label' => '隱藏這個產品',
          'desc' => '目錄頁不要顯示這個產品，但是可以從網址列看到這個產品頁',
        ),
    ),
  'id' => array( 
      'name' => 'id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'primary' => true,
          'autoIncrement' => true,
        ),
    ),
);
        $this->columnNames     = array( 
  'id',
  'name',
  'subtitle',
  'sn',
  'description',
  'content',
  'category_id',
  'is_cover',
  'sellable',
  'orig_price',
  'price',
  'external_link',
  'token',
  'hide',
);
        $this->primaryKey      = 'id';
        $this->table           = 'products';
        $this->modelClass      = 'ProductBundle\\Model\\Product';
        $this->collectionClass = 'ProductBundle\\Model\\ProductCollection';
        $this->label           = 'Product';
        $this->relations       = array( 
  'product_features' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductFeatureSchema',
    ),
)),
  'features' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'relation_junction' => 'product_features',
      'relation_foreign' => 'feature',
    ),
)),
  'product_products' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductProductSchema',
      'order' => array( 
          array( 
              'ordering',
              'ASC',
            ),
        ),
    ),
)),
  'related_products' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'relation_junction' => 'product_products',
      'relation_foreign' => 'related_product',
    ),
)),
  'images' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductImageSchema',
      'order' => array( 
          array( 
              'ordering',
              'ASC',
            ),
        ),
    ),
)),
  'properties' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductPropertySchema',
      'order' => array( 
          array( 
              'ordering',
              'ASC',
            ),
        ),
    ),
)),
  'types' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductTypeSchema',
    ),
)),
  'resources' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ResourceSchema',
    ),
)),
  'subsections' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductSubsectionSchema',
      'order' => array( 
          array( 
              'ordering',
              'ASC',
            ),
        ),
      'renderable' => false,
    ),
)),
  'links' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductLinkSchema',
      'order' => array( 
          array( 
              'ordering',
              'ASC',
            ),
        ),
      'renderable' => false,
    ),
)),
  'product_categories' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductCategorySchema',
      'renderable' => false,
    ),
)),
  'categories' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'relation_junction' => 'product_categories',
      'relation_foreign' => 'category',
      'filter' => function($collection) {
                $collection->order('lang','desc');
                return $collection;
            },
    ),
)),
  'category' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'ProductBundle\\Model\\ProductSchema',
      'self_column' => 'category_id',
      'foreign_schema' => 'ProductBundle\\Model\\CategorySchema',
      'foreign_column' => 'id',
    ),
)),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

    /**
     * Code block for message id parser.
     */
    private function __() {
        _('Product');
        _('產品名稱');
        _('產品副標題');
        _('產品序號');
        _('產品類別');
        _('封面產品');
        _('可販售');
        _('產品原價');
        _('產品售價');
        _('外部連結');
        _('秘密編號');
        _('隱藏這個產品');
    }

}
