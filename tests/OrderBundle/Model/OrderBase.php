<?php
namespace OrderBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\BaseModel;
class OrderBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderSchemaProxy';
    const COLLECTION_CLASS = 'OrderBundle\\Model\\OrderCollection';
    const MODEL_CLASS = 'OrderBundle\\Model\\Order';
    const TABLE = 'orders';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM orders WHERE id = :id';
    public static $column_names = array (
      0 => 'id',
      1 => 'sum',
      2 => 'qty',
    );
    public static $column_hash = array (
      'id' => 1,
      'sum' => 1,
      'qty' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('OrderBundle\\Model\\OrderSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getSum()
    {
            return $this->get('sum');
    }
    public function getQty()
    {
            return $this->get('qty');
    }
}
