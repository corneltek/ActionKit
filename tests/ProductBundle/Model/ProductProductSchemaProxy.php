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

class ProductProductSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'product_id',
  1 => 'related_product_id',
  2 => 'id',
);
    public static $column_hash = array (
  'product_id' => 1,
  'related_product_id' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'product_id',
  1 => 'related_product_id',
  2 => 'id',
);

        const schema_class = 'ProductBundle\\Model\\ProductProductSchema';
        const collection_class = 'ProductBundle\\Model\\ProductProductCollection';
        const model_class = 'ProductBundle\\Model\\ProductProduct';
        const model_name = 'ProductProduct';
        const model_namespace = 'ProductBundle\\Model';
        const primary_key = 'id';
        const table = 'product_products';
        const label = 'ProductProduct';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'isa' => 'int',
          'type' => 'integer',
          'primary' => NULL,
          'refer' => 'ProductBundle\\Model\\Product',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'label' => '產品',
        ),
    ),
  'related_product_id' => array( 
      'name' => 'related_product_id',
      'attributes' => array( 
          'isa' => 'int',
          'type' => 'integer',
          'primary' => NULL,
          'refer' => 'ProductBundle\\Model\\Product',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'label' => '關連產品',
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
  'product_id',
  'related_product_id',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_products';
        $this->modelClass      = 'ProductBundle\\Model\\ProductProduct';
        $this->collectionClass = 'ProductBundle\\Model\\ProductProductCollection';
        $this->label           = 'ProductProduct';
        $this->relations       = array( 
  'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'self_schema' => 'ProductBundle\\Model\\ProductProductSchema',
      'self_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'id',
    ),
  'accessor' => 'product',
  'where' => NULL,
)),
  'related_product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'self_schema' => 'ProductBundle\\Model\\ProductProductSchema',
      'self_column' => 'related_product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'id',
    ),
  'accessor' => 'related_product',
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
        _('ProductProduct');
        _('產品');
        _('關連產品');
    }

}
