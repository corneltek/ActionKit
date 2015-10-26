<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class ProductTypeBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductTypeSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductTypeCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductType';
    const TABLE = 'product_types';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_types WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'name',
      3 => 'quantity',
      4 => 'comment',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'name' => 1,
      'quantity' => 1,
      'comment' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'product_types';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductTypeSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
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
}
