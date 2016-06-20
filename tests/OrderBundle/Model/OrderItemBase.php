<?php
namespace OrderBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class OrderItemBase
    extends BaseModel
{
    const SCHEMA_CLASS = 'OrderBundle\\Model\\OrderItemSchema';
    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderItemSchemaProxy';
    const COLLECTION_CLASS = 'OrderBundle\\Model\\OrderItemCollection';
    const MODEL_CLASS = 'OrderBundle\\Model\\OrderItem';
    const TABLE = 'order_items';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM order_items WHERE id = :id LIMIT 1';
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
    protected $table = 'order_items';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('OrderBundle\\Model\\OrderItemSchemaProxy');
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
