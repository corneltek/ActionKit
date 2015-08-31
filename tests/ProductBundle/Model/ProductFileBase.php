<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductFileBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductFileSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductFileCollection';
    const model_class = 'ProductBundle\\Model\\ProductFile';
    const table = 'product_files';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'product_id',
      1 => 'title',
      2 => 'file',
      3 => 'id',
    );
    public static $column_hash = array (
      'product_id' => 1,
      'title' => 1,
      'file' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductFileSchemaProxy');
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
    public function getId()
    {
            return $this->get('id');
    }
}
