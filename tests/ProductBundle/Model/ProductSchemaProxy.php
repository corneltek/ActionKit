<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductSchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Product';
    const model_name = 'Product';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'products';
    const LABEL = 'Product';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'subtitle',
      3 => 'sn',
      4 => 'description',
      5 => 'content',
      6 => 'category_id',
      7 => 'is_cover',
      8 => 'sellable',
      9 => 'orig_price',
      10 => 'price',
      11 => 'external_link',
      12 => 'token',
      13 => 'ordering',
      14 => 'hide',
    );
    public static $column_hash = array (
      'id' => 1,
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
      'ordering' => 1,
      'hide' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'name',
      2 => 'subtitle',
      3 => 'sn',
      4 => 'description',
      5 => 'content',
      6 => 'category_id',
      7 => 'is_cover',
      8 => 'sellable',
      9 => 'orig_price',
      10 => 'price',
      11 => 'external_link',
      12 => 'token',
      13 => 'ordering',
      14 => 'hide',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'name',
      2 => 'subtitle',
      3 => 'sn',
      4 => 'description',
      5 => 'content',
      6 => 'category_id',
      7 => 'is_cover',
      8 => 'sellable',
      9 => 'orig_price',
      10 => 'price',
      11 => 'external_link',
      12 => 'token',
      13 => 'ordering',
      14 => 'hide',
    );
    public $primaryKey = 'id';
    public $table = 'products';
    public $modelClass = 'ProductBundle\\Model\\Product';
    public $collectionClass = 'ProductBundle\\Model\\ProductCollection';
    public $label = 'Product';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product_features' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductFeatureSchema',
        ),
      'accessor' => 'product_features',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'features' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 4,
          'relation_junction' => 'product_features',
          'relation_foreign' => 'feature',
        ),
      'accessor' => 'features',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'product_products' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductProductSchema',
        ),
      'accessor' => 'product_products',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'related_products' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 4,
          'relation_junction' => 'product_products',
          'relation_foreign' => 'related_product',
        ),
      'accessor' => 'related_products',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'images' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductImageSchema',
        ),
      'accessor' => 'images',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'properties' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductPropertySchema',
        ),
      'accessor' => 'properties',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'types' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductTypeSchema',
        ),
      'accessor' => 'types',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'resources' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ResourceSchema',
        ),
      'accessor' => 'resources',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'subsections' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductSubsectionSchema',
          'renderable' => false,
        ),
      'accessor' => 'subsections',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'links' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductLinkSchema',
          'renderable' => false,
        ),
      'accessor' => 'links',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'product_categories' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductCategorySchema',
          'renderable' => false,
        ),
      'accessor' => 'product_categories',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'categories' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 4,
          'relation_junction' => 'product_categories',
          'relation_foreign' => 'category',
          'filter' => function($collection) {
                    return $collection;
                },
        ),
      'accessor' => 'categories',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'category' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductSchema',
          'self_column' => 'category_id',
          'foreign_schema' => 'ProductBundle\\Model\\CategorySchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'category',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
    );
        $this->columns[ 'id' ] = new RuntimeColumn('id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'autoIncrement' => true,
        ),
      'name' => 'id',
      'primary' => true,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'autoIncrement' => true,
    ));
        $this->columns[ 'name' ] = new RuntimeColumn('name',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 256,
          'label' => '產品名稱',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 30,
            ),
        ),
      'name' => 'name',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 256,
      'label' => '產品名稱',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
          'size' => 30,
        ),
    ));
        $this->columns[ 'subtitle' ] = new RuntimeColumn('subtitle',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 256,
          'label' => '產品副標題',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 60,
            ),
        ),
      'name' => 'subtitle',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 256,
      'label' => '產品副標題',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
          'size' => 60,
        ),
    ));
        $this->columns[ 'sn' ] = new RuntimeColumn('sn',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
          'label' => '產品序號',
        ),
      'name' => 'sn',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
      'label' => '產品序號',
    ));
        $this->columns[ 'description' ] = new RuntimeColumn('description',array( 
      'locales' => NULL,
      'attributes' => array( 
        ),
      'name' => 'description',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'text',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
    ));
        $this->columns[ 'content' ] = new RuntimeColumn('content',array( 
      'locales' => NULL,
      'attributes' => array( 
          'renderAs' => 'TextareaInput',
          'widgetAttributes' => array( 
            ),
        ),
      'name' => 'content',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'text',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'renderAs' => 'TextareaInput',
      'widgetAttributes' => array( 
        ),
    ));
        $this->columns[ 'category_id' ] = new RuntimeColumn('category_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'refer' => 'ProductBundle\\Model\\CategorySchema',
          'label' => '產品類別',
        ),
      'name' => 'category_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'refer' => 'ProductBundle\\Model\\CategorySchema',
      'label' => '產品類別',
    ));
        $this->columns[ 'is_cover' ] = new RuntimeColumn('is_cover',array( 
      'locales' => NULL,
      'attributes' => array( 
          'renderAs' => 'CheckboxInput',
          'widgetAttributes' => array( 
            ),
          'label' => '封面產品',
        ),
      'name' => 'is_cover',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'boolean',
      'isa' => 'bool',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'renderAs' => 'CheckboxInput',
      'widgetAttributes' => array( 
        ),
      'label' => '封面產品',
    ));
        $this->columns[ 'sellable' ] = new RuntimeColumn('sellable',array( 
      'locales' => NULL,
      'attributes' => array( 
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
      'name' => 'sellable',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'boolean',
      'isa' => 'bool',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
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
    ));
        $this->columns[ 'orig_price' ] = new RuntimeColumn('orig_price',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => '產品原價',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'placeholder' => '如: 3200',
            ),
        ),
      'name' => 'orig_price',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => '產品原價',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
          'placeholder' => '如: 3200',
        ),
    ));
        $this->columns[ 'price' ] = new RuntimeColumn('price',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => '產品售價',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'placeholder' => '如: 2800',
            ),
        ),
      'name' => 'price',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => '產品售價',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
          'placeholder' => '如: 2800',
        ),
    ));
        $this->columns[ 'external_link' ] = new RuntimeColumn('external_link',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 256,
          'label' => '外部連結',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 70,
              'placeholder' => '如: http://....',
            ),
        ),
      'name' => 'external_link',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 256,
      'label' => '外部連結',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
          'size' => 70,
          'placeholder' => '如: http://....',
        ),
    ));
        $this->columns[ 'token' ] = new RuntimeColumn('token',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
          'label' => '秘密編號',
          'desc' => '使用者必須透過這組秘密編號的網址才能看到這個產品。',
        ),
      'name' => 'token',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
      'label' => '秘密編號',
      'desc' => '使用者必須透過這組秘密編號的網址才能看到這個產品。',
    ));
        $this->columns[ 'ordering' ] = new RuntimeColumn('ordering',array( 
      'locales' => NULL,
      'attributes' => array( 
          'default' => 0,
          'label' => '排序編號',
        ),
      'name' => 'ordering',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'default' => 0,
      'label' => '排序編號',
    ));
        $this->columns[ 'hide' ] = new RuntimeColumn('hide',array( 
      'locales' => NULL,
      'attributes' => array( 
          'default' => false,
          'label' => '隱藏這個產品',
          'desc' => '目錄頁不要顯示這個產品，但是可以從網址列看到這個產品頁',
        ),
      'name' => 'hide',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'boolean',
      'isa' => 'bool',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'default' => false,
      'label' => '隱藏這個產品',
      'desc' => '目錄頁不要顯示這個產品，但是可以從網址列看到這個產品頁',
    ));
    }
}
