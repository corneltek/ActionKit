<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\BaseModel;
class ProductProductBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductProductSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductProductCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProduct';
    const TABLE = 'product_products';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_products WHERE id = :id';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'related_product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'related_product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductProductSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
    public function getRelatedProductId()
    {
            return $this->get('related_product_id');
    }
}
