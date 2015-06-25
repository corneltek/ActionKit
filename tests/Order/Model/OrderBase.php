<?php
namespace Order\Model;
use LazyRecord\BaseModel;
class OrderBase
    extends BaseModel
{
    const schema_proxy_class = 'Order\\Model\\OrderSchemaProxy';
    const collection_class = 'Order\\Model\\OrderCollection';
    const model_class = 'Order\\Model\\Order';
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
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Order\\Model\\OrderSchemaProxy');
    }
    public function getSum()
    {
        if (isset($this->_data['sum'])) {
            return $this->_data['sum'];
        }
    }
    public function getQty()
    {
        if (isset($this->_data['qty'])) {
            return $this->_data['qty'];
        }
    }
    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }
}
