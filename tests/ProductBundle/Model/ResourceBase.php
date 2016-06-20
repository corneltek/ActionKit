<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class ResourceBase
    extends BaseModel
{
    const SCHEMA_CLASS = 'ProductBundle\\Model\\ResourceSchema';
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ResourceSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ResourceCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Resource';
    const TABLE = 'product_resources';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_resources WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'url',
      3 => 'html',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'url' => 1,
      'html' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'product_resources';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ResourceSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
    public function getUrl()
    {
            return $this->get('url');
    }
    public function getHtml()
    {
            return $this->get('html');
    }
}
