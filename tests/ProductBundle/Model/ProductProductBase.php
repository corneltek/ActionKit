<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductProductBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductProductSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductProductCollection';
    const model_class = 'ProductBundle\\Model\\ProductProduct';
    const table = 'product_products';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
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
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductProductSchemaProxy');
    }
    public function getProductId()
    {
        if (isset($this->_data['product_id'])) {
            return $this->_data['product_id'];
        }
    }
    public function getRelatedProductId()
    {
        if (isset($this->_data['related_product_id'])) {
            return $this->_data['related_product_id'];
        }
    }
    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }
}
