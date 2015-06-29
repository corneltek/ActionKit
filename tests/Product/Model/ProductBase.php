<?php
namespace Product\Model;
use LazyRecord\BaseModel;
class ProductBase
    extends BaseModel
{
    const schema_proxy_class = 'Product\\Model\\ProductSchemaProxy';
    const collection_class = 'Product\\Model\\ProductCollection';
    const model_class = 'Product\\Model\\Product';
    const table = 'products';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'name',
      1 => 'category_id',
      2 => 'cover_image',
      3 => 'ordering',
      4 => 'id',
    );
    public static $column_hash = array (
      'name' => 1,
      'category_id' => 1,
      'cover_image' => 1,
      'ordering' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Product\\Model\\ProductSchemaProxy');
    }
    public function getName()
    {
        if (isset($this->_data['name'])) {
            return $this->_data['name'];
        }
    }
    public function getCategoryId()
    {
        if (isset($this->_data['category_id'])) {
            return $this->_data['category_id'];
        }
    }
    public function getCoverImage()
    {
        if (isset($this->_data['cover_image'])) {
            return $this->_data['cover_image'];
        }
    }
    public function getOrdering()
    {
        if (isset($this->_data['ordering'])) {
            return $this->_data['ordering'];
        }
    }
    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }
}
