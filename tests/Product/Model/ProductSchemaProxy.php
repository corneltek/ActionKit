<?php
namespace Product\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'name',
  1 => 'category_id',
  2 => 'cover_image',
  3 => 'id',
);
    public static $column_hash = array (
  'name' => 1,
  'category_id' => 1,
  'cover_image' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'name',
  1 => 'category_id',
  2 => 'cover_image',
  3 => 'id',
);

    const schema_class = 'Product\\Model\\ProductSchema';
    const collection_class = 'Product\\Model\\ProductCollection';
    const model_class = 'Product\\Model\\Product';
    const model_name = 'Product';
    const model_namespace = 'Product\\Model';
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
          'label' => 'Name',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 30,
            ),
        ),
    ),
  'category_id' => array( 
      'name' => 'category_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'refer' => 'Product\\Model\\Category',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'label' => '產品類別',
        ),
    ),
  'cover_image' => array( 
      'name' => 'cover_image',
      'attributes' => array( 
          'type' => 'varchar(250)',
          'isa' => 'str',
          'size' => 250,
          'label' => '首頁封面圖',
          'contentType' => 'ImageFile',
          'renderAs' => 'ThumbImageFileInput',
          'widgetAttributes' => array( 
            ),
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
  'category_id',
  'cover_image',
);
        $this->primaryKey      = 'id';
        $this->table           = 'products';
        $this->modelClass      = 'Product\\Model\\Product';
        $this->collectionClass = 'Product\\Model\\ProductCollection';
        $this->label           = 'Product';
        $this->relations       = array( 
  'types' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'Product\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'Product\\Model\\ProductTypeSchema',
    ),
)),
  'product_categories' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'Product\\Model\\ProductSchema',
      'foreign_column' => 'product_id',
      'foreign_schema' => 'Product\\Model\\ProductCategorySchema',
    ),
)),
  'categories' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'relation_junction' => 'product_categories',
      'relation_foreign' => 'category',
    ),
)),
  'category' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'Product\\Model\\ProductSchema',
      'self_column' => 'category_id',
      'foreign_schema' => 'Product\\Model\\CategorySchema',
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
        _('Name');
        _('產品類別');
        _('首頁封面圖');
    }

}
