<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\BaseModel;
class ProductLinkBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductLinkSchemaProxy';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductLinkCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductLink';
    const TABLE = 'product_links';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM product_links WHERE id = :id';
    public static $column_names = array (
      0 => 'id',
      1 => 'label',
      2 => 'url',
      3 => 'product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'label' => 1,
      'url' => 1,
      'product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('ProductBundle\\Model\\ProductLinkSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getLabel()
    {
            return $this->get('label');
    }
    public function getUrl()
    {
            return $this->get('url');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
}
