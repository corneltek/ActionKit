<?php
namespace ProductBundle\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class CategorySchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'name',
  1 => 'description',
  2 => 'parent_id',
  3 => 'hide',
  4 => 'thumb',
  5 => 'image',
  6 => 'handle',
  7 => 'id',
);
    public static $column_hash = array (
  'name' => 1,
  'description' => 1,
  'parent_id' => 1,
  'hide' => 1,
  'thumb' => 1,
  'image' => 1,
  'handle' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'name',
  1 => 'description',
  2 => 'parent_id',
  3 => 'hide',
  4 => 'thumb',
  5 => 'image',
  6 => 'handle',
  7 => 'id',
);

    const schema_class = 'ProductBundle\\Model\\CategorySchema';
    const collection_class = 'ProductBundle\\Model\\CategoryCollection';
    const model_class = 'ProductBundle\\Model\\Category';
    const model_name = 'Category';
    const model_namespace = 'ProductBundle\\Model';
    const primary_key = 'id';
    const table = 'product_categories';
    const label = 'Category';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(130)',
          'isa' => 'str',
          'size' => 130,
          'label' => '產品類別名稱',
          'required' => 1,
        ),
    ),
  'description' => array( 
      'name' => 'description',
      'attributes' => array( 
          'type' => 'text',
          'isa' => 'str',
          'label' => '產品類別敘述',
          'renderAs' => 'TextareaInput',
          'widgetAttributes' => array( 
              'class' => '+=mceEditor',
            ),
        ),
    ),
  'parent_id' => array( 
      'name' => 'parent_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'refer' => 'ProductBundle\\Model\\CategorySchema',
          'label' => '父類別',
          'default' => 0,
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
              'allow_empty' => 0,
            ),
        ),
    ),
  'hide' => array( 
      'name' => 'hide',
      'attributes' => array( 
          'type' => 'boolean',
          'isa' => 'bool',
          'label' => '隱藏這個類別',
        ),
    ),
  'thumb' => array( 
      'name' => 'thumb',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
          'label' => '縮圖',
        ),
    ),
  'image' => array( 
      'name' => 'image',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
          'label' => '圖片',
        ),
    ),
  'handle' => array( 
      'name' => 'handle',
      'attributes' => array( 
          'type' => 'varchar(32)',
          'isa' => 'str',
          'size' => 32,
          'label' => '程式用操作碼',
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
  'description',
  'parent_id',
  'hide',
  'thumb',
  'image',
  'handle',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_categories';
        $this->modelClass      = 'ProductBundle\\Model\\Category';
        $this->collectionClass = 'ProductBundle\\Model\\CategoryCollection';
        $this->label           = 'Category';
        $this->relations       = array( 
  'subcategories' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\CategorySchema',
      'foreign_column' => 'parent_id',
      'foreign_schema' => 'ProductBundle\\Model\\CategorySchema',
    ),
)),
  'parent' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'ProductBundle\\Model\\CategorySchema',
      'self_column' => 'parent_id',
      'foreign_schema' => 'ProductBundle\\Model\\CategorySchema',
      'foreign_column' => 'id',
    ),
)),
  'category_products' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'ProductBundle\\Model\\CategorySchema',
      'foreign_column' => 'category_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductCategorySchema',
    ),
)),
  'products' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'relation_junction' => 'category_products',
      'relation_foreign' => 'product',
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
        _('Category');
        _('產品類別名稱');
        _('產品類別敘述');
        _('父類別');
        _('隱藏這個類別');
        _('縮圖');
        _('圖片');
        _('程式用操作碼');
    }

}
