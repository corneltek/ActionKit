<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductLinkBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductLinkSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductLinkCollection';
    const model_class = 'ProductBundle\\Model\\ProductLink';
    const table = 'product_links';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'label',
      1 => 'url',
      2 => 'product_id',
      3 => 'id',
    );
    public static $column_hash = array (
      'label' => 1,
      'url' => 1,
      'product_id' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductLinkSchemaProxy');
    }
    public function getLabel()
    {
        if (isset($this->_data['label'])) {
            return $this->_data['label'];
        }
    }
    public function getUrl()
    {
        if (isset($this->_data['url'])) {
            return $this->_data['url'];
        }
    }
    public function getProductId()
    {
        if (isset($this->_data['product_id'])) {
            return $this->_data['product_id'];
        }
    }
    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }
}
