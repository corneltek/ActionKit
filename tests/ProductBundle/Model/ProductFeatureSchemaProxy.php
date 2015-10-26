<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductFeatureSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductFeatureSchema';
    const model_name = 'ProductFeature';
    const model_namespace = 'ProductBundle\\Model';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductFeatureCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFeature';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_feature_junction';
    const LABEL = 'ProductFeature';
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'feature_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'feature_id',
    );
    public $primaryKey = 'id';
    public $columnNamesIncludeVirtual = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'feature_id',
    );
    public $label = 'ProductFeature';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductFeatureSchema',
          'self_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'product',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'feature' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductFeatureSchema',
          'self_column' => 'feature_id',
          'foreign_schema' => 'ProductBundle\\Model\\FeatureSchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'feature',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
    );
        $this->columns[ 'id' ] = new RuntimeColumn('id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'autoIncrement' => true,
        ),
      'name' => 'id',
      'primary' => true,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'autoIncrement' => true,
    ));
        $this->columns[ 'product_id' ] = new RuntimeColumn('product_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => 'Product Id',
          'refer' => 'ProductBundle\\Model\\Product',
        ),
      'name' => 'product_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => NULL,
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => 'Product Id',
      'refer' => 'ProductBundle\\Model\\Product',
    ));
        $this->columns[ 'feature_id' ] = new RuntimeColumn('feature_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => 'Feature Id',
          'refer' => 'ProductBundle\\Model\\Feature',
        ),
      'name' => 'feature_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => NULL,
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => 'Feature Id',
      'refer' => 'ProductBundle\\Model\\Feature',
    ));
    }
}
