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
            return $this->get('product_id');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getQuantity()
    {
            return $this->get('quantity');
    }
    public function getComment()
    {
            return $this->get('comment');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
