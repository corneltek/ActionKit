<?php
namespace Product\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductCategorySchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'product_id',
  1 => 'category_id',
  2 => 'id',
);
    public static $column_hash = array (
  'product_id' => 1,
  'category_id' => 1,
  'id' => 1,
);
    public static $column_names_include_virtual = array (
  0 => 'product_id',
  1 => 'category_id',
  2 => 'id',
);

    const schema_class = 'Product\\Model\\ProductCategorySchema';
    const collection_class = 'Product\\Model\\ProductCategoryCollection';
    const model_class = 'Product\\Model\\ProductCategory';
    const model_name = 'ProductCategory';
    const model_namespace = 'Product\\Model';
    const primary_key = 'id';
    const table = 'product_category_junction';
    const label = 'ProductCategory';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
        ),
    ),
  'category_id' => array( 
      'name' => 'category_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
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
  'product_id',
  'category_id',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_category_junction';
        $this->modelClass      = 'Product\\Model\\ProductCategory';
        $this->collectionClass = 'Product\\Model\\ProductCategoryCollection';
        $this->label           = 'ProductCategory';
        $this->relations       = array( 
  'category' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'Product\\Model\\ProductCategorySchema',
      'self_column' => 'category_id',
      'foreign_schema' => 'Product\\Model\\CategorySchema',
      'foreign_column' => 'id',
    ),
)),
  'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'Product\\Model\\ProductCategorySchema',
      'self_column' => 'product_id',
      'foreign_schema' => 'Product\\Model\\ProductSchema',
      'foreign_column' => 'id',
    ),
)),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
