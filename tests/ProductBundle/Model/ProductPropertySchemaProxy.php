<?php
namespace ProductBundle\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductPropertySchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'name',
  1 => 'value',
  2 => 'product_id',
  3 => 'id',
);
    public static $column_hash = array (
  'name' => 1,
  'value' => 1,
  'product_id' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'name',
  1 => 'value',
  2 => 'product_id',
  3 => 'id',
);

    const schema_class = 'ProductBundle\\Model\\ProductPropertySchema';
    const collection_class = 'ProductBundle\\Model\\ProductPropertyCollection';
    const model_class = 'ProductBundle\\Model\\ProductProperty';
    const model_name = 'ProductProperty';
    const model_namespace = 'ProductBundle\\Model';
    const primary_key = 'id';
    const table = 'product_properties';
    const label = 'ProductProperty';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
        ),
    ),
  'value' => array( 
      'name' => 'value',
      'attributes' => array( 
          'type' => 'varchar(512)',
          'isa' => 'str',
          'size' => 512,
        ),
    ),
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'refer' => 'ProductBundle\\Model\\ProductSchema',
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
  'value',
  'product_id',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_properties';
        $this->modelClass      = 'ProductBundle\\Model\\ProductProperty';
        $this->collectionClass = 'ProductBundle\\Model\\ProductPropertyCollection';
        $this->label           = 'ProductProperty';
        $this->relations       = array( 
  'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'ProductBundle\\Model\\ProductPropertySchema',
      'self_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
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
        _('ProductProperty');
    }

}
