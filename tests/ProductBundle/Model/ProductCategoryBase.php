<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class ProductCategoryBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductCategorySchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductCategoryCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductCategory';
    const TABLE = 'product_category_junction';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_category_junction WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'category_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'category_id' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'product_category_junction';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductCategorySchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
    public function getCategoryId()
    {
            return $this->get('category_id');
    }
}
