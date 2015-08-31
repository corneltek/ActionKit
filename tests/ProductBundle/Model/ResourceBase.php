<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ResourceBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ResourceSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ResourceCollection';
    const model_class = 'ProductBundle\\Model\\Resource';
    const table = 'product_resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'product_id',
      1 => 'url',
      2 => 'html',
      3 => 'id',
    );
    public static $column_hash = array (
      'product_id' => 1,
      'url' => 1,
      'html' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ResourceSchemaProxy');
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
    public function getId()
    {
            return $this->get('id');
    }
}
