<?php
namespace OrderBundle\Model;
use LazyRecord\BaseModel;
class OrderItemBase
    extends BaseModel
{
    const schema_proxy_class = 'OrderBundle\\Model\\OrderItemSchemaProxy';
    const collection_class = 'OrderBundle\\Model\\OrderItemCollection';
    const model_class = 'OrderBundle\\Model\\OrderItem';
    const table = 'order_items';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'id',
      1 => 'quantity',
      2 => 'subtotal',
    );
    public static $column_hash = array (
      'id' => 1,
      'quantity' => 1,
      'subtotal' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('OrderBundle\\Model\\OrderItemSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getQuantity()
    {
            return $this->get('quantity');
    }
    public function getSubtotal()
    {
            return $this->get('subtotal');
    }
}
