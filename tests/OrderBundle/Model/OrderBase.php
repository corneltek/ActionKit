<?php
namespace OrderBundle\Model;
use LazyRecord\BaseModel;
class OrderBase
    extends BaseModel
{
    const schema_proxy_class = 'OrderBundle\\Model\\OrderSchemaProxy';
    const collection_class = 'OrderBundle\\Model\\OrderCollection';
    const model_class = 'OrderBundle\\Model\\Order';
    const table = 'orders';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'sum',
      1 => 'qty',
      2 => 'id',
    );
    public static $column_hash = array (
      'sum' => 1,
      'qty' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('OrderBundle\\Model\\OrderSchemaProxy');
    }
    public function getSum()
    {
            return $this->get('sum');
    }
    public function getQty()
    {
            return $this->get('qty');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
