<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class ProductSubsectionBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSubsectionSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductSubsectionCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductSubsection';
    const TABLE = 'product_subsections';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_subsections WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'title',
      2 => 'cover_image',
      3 => 'content',
      4 => 'product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'title' => 1,
      'cover_image' => 1,
      'content' => 1,
      'product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'product_subsections';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductSubsectionSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getTitle()
    {
            return $this->get('title');
    }
    public function getCoverImage()
    {
            return $this->get('cover_image');
    }
    public function getContent()
    {
            return $this->get('content');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
}
