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

class ProductImageSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'product_id',
  1 => 'title',
  2 => 'large',
  3 => 'id',
);
    public static $column_hash = array (
  'product_id' => 1,
  'title' => 1,
  'large' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'product_id',
  1 => 'title',
  2 => 'large',
  3 => 'id',
);

        const schema_class = 'ProductBundle\\Model\\ProductImageSchema';
        const collection_class = 'ProductBundle\\Model\\ProductImageCollection';
        const model_class = 'ProductBundle\\Model\\ProductImage';
        const model_name = 'ProductImage';
        const model_namespace = 'ProductBundle\\Model';
        const primary_key = 'id';
        const table = 'product_images';
        const label = '產品圖';

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
  'title' => array( 
      'name' => 'title',
      'attributes' => array( 
          'isa' => 'str',
          'type' => 'varchar',
          'primary' => NULL,
          'length' => 130,
          'label' => '圖片標題',
        ),
    ),
  'large' => array( 
      'name' => 'large',
      'attributes' => array( 
          'isa' => 'str',
          'type' => 'varchar',
          'primary' => NULL,
          'length' => 130,
          'label' => '最大圖',
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
  'title',
  'large',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_images';
        $this->modelClass      = 'ProductBundle\\Model\\ProductImage';
        $this->collectionClass = 'ProductBundle\\Model\\ProductImageCollection';
        $this->label           = '產品圖';
        $this->relations       = array( 
  'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 3,
      'self_schema' => 'ProductBundle\\Model\\ProductImageSchema',
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
        _('產品圖');
        _('產品');
        _('圖片標題');
        _('最大圖');
    }

}
