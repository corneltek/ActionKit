<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductTypeBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductTypeSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductTypeCollection';
    const model_class = 'ProductBundle\\Model\\ProductType';
    const table = 'product_types';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'product_id',
      1 => 'name',
      2 => 'quantity',
      3 => 'comment',
      4 => 'id',
    );
    public static $column_hash = array (
      'product_id' => 1,
      'name' => 1,
      'quantity' => 1,
      'comment' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductTypeSchemaProxy');
    }
    public function getProductId()
    {
        if (isset($this->_data['product_id'])) {
            return $this->_data['product_id'];
        }
    }
    public function getName()
    {
        if (isset($this->_data['name'])) {
            return $this->_data['name'];
        }
    }
    public function getQuantity()
    {
        if (isset($this->_data['quantity'])) {
            return $this->_data['quantity'];
        }
    }
    public function getComment()
    {
        if (isset($this->_data['comment'])) {
            return $this->_data['comment'];
        }
    }
    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }
}
