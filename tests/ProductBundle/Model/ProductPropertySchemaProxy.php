<?php
/**
 * THIS FILE IS AUTO-GENERATED BY LAZYRECORD,
 * PLEASE DO NOT MODIFY THIS FILE DIRECTLY.
 * 
 * Last Modified: February 15th at 3:37pm
 */
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
          'isa' => 'str',
          'type' => 'varchar',
          'primary' => NULL,
          'length' => 64,
        ),
    ),
  'value' => array( 
      'name' => 'value',
      'attributes' => array( 
          'isa' => 'str',
          'type' => 'varchar',
          'primary' => NULL,
          'length' => 512,
        ),
    ),
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'isa' => 'int',
          'type' => 'integer',
          'primary' => NULL,
          'refer' => 'ProductBundle\\Model\\ProductSchema',
        ),
    ),
  'id' => array( 
      'name' => 'id',
      'attributes' => array( 
          'isa' => 'int',
          'type' => 'integer',
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
      'type' => 3,
      'self_schema' => 'ProductBundle\\Model\\ProductPropertySchema',
      'self_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'id',
    ),
  'accessor' => 'product',
  'where' => NULL,
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
