<?php
namespace Product\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductSchemaProxy extends RuntimeSchema
{

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
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
