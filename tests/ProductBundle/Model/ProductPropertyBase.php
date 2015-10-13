<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\BaseModel;
class ProductPropertyBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductPropertySchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductPropertyCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProperty';
    const TABLE = 'product_properties';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_properties WHERE id = :id';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'val',
      3 => 'product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'val' => 1,
      'product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductPropertySchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getVal()
    {
            return $this->get('val');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
}
