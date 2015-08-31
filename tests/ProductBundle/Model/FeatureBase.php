<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class FeatureBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\FeatureSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\FeatureCollection';
    const model_class = 'ProductBundle\\Model\\Feature';
    const table = 'product_features';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'name',
      1 => 'description',
      2 => 'image',
      3 => 'id',
    );
    public static $column_hash = array (
      'name' => 1,
      'description' => 1,
      'image' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\FeatureSchemaProxy');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getDescription()
    {
            return $this->get('description');
    }
    public function getImage()
    {
            return $this->get('image');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
