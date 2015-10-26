<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class ProductFileBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductFileSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductFileCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFile';
    const TABLE = 'product_files';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_files WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'file',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'title' => 1,
      'file' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'product_files';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductFileSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
    public function getTitle()
    {
            return $this->get('title');
    }
    public function getFile()
    {
            return $this->get('file');
    }
}
