<?php
namespace ProductBundle\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductFeatureSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'product_id',
  1 => 'feature_id',
  2 => 'id',
);
    public static $column_hash = array (
  'product_id' => 1,
  'feature_id' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'product_id',
  1 => 'feature_id',
  2 => 'id',
);

    const schema_class = 'ProductBundle\\Model\\ProductFeatureSchema';
    const collection_class = 'ProductBundle\\Model\\ProductFeatureCollection';
    const model_class = 'ProductBundle\\Model\\ProductFeature';
    const model_name = 'ProductFeature';
    const model_namespace = 'ProductBundle\\Model';
    const primary_key = 'id';
    const table = 'product_feature_junction';
    const label = 'ProductFeature';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'type' => 'text',
          'isa' => 'str',
          'label' => 'Product Id',
          'refer' => 'ProductBundle\\Model\\Product',
        ),
    ),
  'feature_id' => array( 
      'name' => 'feature_id',
      'attributes' => array( 
          'type' => 'text',
          'isa' => 'str',
          'label' => 'Feature Id',
          'refer' => 'ProductBundle\\Model\\Feature',
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
  'feature_id',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_feature_junction';
        $this->modelClass      = 'ProductBundle\\Model\\ProductFeature';
        $this->collectionClass = 'ProductBundle\\Model\\ProductFeatureCollection';
        $this->label           = 'ProductFeature';
        $this->relations       = array( 
  'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'ProductBundle\\Model\\ProductFeatureSchema',
      'self_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'id',
    ),
)),
  'feature' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'ProductBundle\\Model\\ProductFeatureSchema',
      'self_column' => 'feature_id',
      'foreign_schema' => 'ProductBundle\\Model\\FeatureSchema',
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
        _('ProductFeature');
        _('Product Id');
        _('Feature Id');
    }

}
