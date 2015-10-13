<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\BaseModel;
class FeatureBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\FeatureSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\FeatureCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Feature';
    const TABLE = 'product_features';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_features WHERE id = :id';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'image',
    );
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'description' => 1,
      'image' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\FeatureSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
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
}
