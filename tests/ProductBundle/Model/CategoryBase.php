<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class CategoryBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\CategorySchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\CategoryCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Category';
    const TABLE = 'product_categories';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_categories WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'parent_id',
      4 => 'hide',
      5 => 'thumb',
      6 => 'image',
      7 => 'handle',
    );
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'description' => 1,
      'parent_id' => 1,
      'hide' => 1,
      'thumb' => 1,
      'image' => 1,
      'handle' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'product_categories';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\CategorySchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getDescription()
    {
            return $this->get('description');
    }
    public function getParentId()
    {
            return $this->get('parent_id');
    }
    public function getHide()
    {
            return $this->get('hide');
    }
    public function getThumb()
    {
            return $this->get('thumb');
    }
    public function getImage()
    {
            return $this->get('image');
    }
    public function getHandle()
    {
            return $this->get('handle');
    }
}
