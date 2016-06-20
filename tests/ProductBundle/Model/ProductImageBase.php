<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class ProductImageBase
    extends BaseModel
{
    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductImageSchema';
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductImageSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductImageCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductImage';
    const TABLE = 'product_images';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_images WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'image',
      4 => 'large',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'title' => 1,
      'image' => 1,
      'large' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'product_images';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductImageSchemaProxy');
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
    public function getImage()
    {
            return $this->get('image');
    }
    public function getLarge()
    {
            return $this->get('large');
    }
}
