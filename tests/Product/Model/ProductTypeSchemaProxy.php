<?php
namespace Product\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductTypeSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'product_id',
  1 => 'name',
  2 => 'id',
);
    public static $column_hash = array (
  'product_id' => 1,
  'name' => 1,
  'id' => 1,
);
    public static $column_names_include_virtual = array (
  0 => 'product_id',
  1 => 'name',
  2 => 'id',
);

    const schema_class = 'Product\\Model\\ProductTypeSchema';
    const collection_class = 'Product\\Model\\ProductTypeCollection';
    const model_class = 'Product\\Model\\ProductType';
    const model_name = 'ProductType';
    const model_namespace = 'Product\\Model';
    const primary_key = 'id';
    const table = 'product_types';
    const label = 'ProductType';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'label' => 'Product',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'refer' => 'Product\\Model\\Product',
        ),
    ),
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(120)',
          'isa' => 'str',
          'size' => 120,
          'required' => true,
          'label' => 'Name',
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
  'name',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_types';
        $this->modelClass      = 'Product\\Model\\ProductType';
        $this->collectionClass = 'Product\\Model\\ProductTypeCollection';
        $this->label           = 'ProductType';
        $this->relations       = array( 
  'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'Product\\Model\\ProductTypeSchema',
      'self_column' => 'product_id',
      'foreign_schema' => 'Product\\Model\\Product',
      'foreign_column' => 'id',
    ),
)),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
