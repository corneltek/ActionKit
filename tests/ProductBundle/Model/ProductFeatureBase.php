<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductFeatureBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductFeatureSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductFeatureCollection';
    const model_class = 'ProductBundle\\Model\\ProductFeature';
    const table = 'product_feature_junction';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
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
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductFeatureSchemaProxy');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
    public function getFeatureId()
    {
            return $this->get('feature_id');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
