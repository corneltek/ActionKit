<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class ProductBase
    extends BaseModel
{
    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductSchema';
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Product';
    const TABLE = 'products';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM products WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'subtitle',
      3 => 'sn',
      4 => 'description',
      5 => 'content',
      6 => 'category_id',
      7 => 'is_cover',
      8 => 'sellable',
      9 => 'orig_price',
      10 => 'price',
      11 => 'external_link',
      12 => 'token',
      13 => 'ordering',
      14 => 'hide',
    );
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'subtitle' => 1,
      'sn' => 1,
      'description' => 1,
      'content' => 1,
      'category_id' => 1,
      'is_cover' => 1,
      'sellable' => 1,
      'orig_price' => 1,
      'price' => 1,
      'external_link' => 1,
      'token' => 1,
      'ordering' => 1,
      'hide' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'products';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getSubtitle()
    {
            return $this->get('subtitle');
    }
    public function getSn()
    {
            return $this->get('sn');
    }
    public function getDescription()
    {
            return $this->get('description');
    }
    public function getContent()
    {
            return $this->get('content');
    }
    public function getCategoryId()
    {
            return $this->get('category_id');
    }
    public function getIsCover()
    {
            return $this->get('is_cover');
    }
    public function getSellable()
    {
            return $this->get('sellable');
    }
    public function getOrigPrice()
    {
            return $this->get('orig_price');
    }
    public function getPrice()
    {
            return $this->get('price');
    }
    public function getExternalLink()
    {
            return $this->get('external_link');
    }
    public function getToken()
    {
            return $this->get('token');
    }
    public function getOrdering()
    {
            return $this->get('ordering');
    }
    public function getHide()
    {
            return $this->get('hide');
    }
}
