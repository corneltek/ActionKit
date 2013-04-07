<?php
namespace Product\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class CategorySchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'name',
  1 => 'id',
);
    public static $column_hash = array (
  'name' => 1,
  'id' => 1,
);
    public static $column_names_include_virtual = array (
  0 => 'name',
  1 => 'id',
);

    const schema_class = 'Product\\Model\\CategorySchema';
    const collection_class = 'Product\\Model\\CategoryCollection';
    const model_class = 'Product\\Model\\Category';
    const model_name = 'Category';
    const model_namespace = 'Product\\Model';
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
          'label' => 'Category Name',
          'required' => 1,
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
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_categories';
        $this->modelClass      = 'Product\\Model\\Category';
        $this->collectionClass = 'Product\\Model\\CategoryCollection';
        $this->label           = 'Category';
        $this->relations       = array( 
  'category_products' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'id',
      'self_schema' => 'Product\\Model\\CategorySchema',
      'foreign_column' => 'category_id',
      'foreign_schema' => 'Product\\Model\\ProductCategorySchema',
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

}
