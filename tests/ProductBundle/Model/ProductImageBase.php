<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductImageBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductImageSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductImageCollection';
    const model_class = 'ProductBundle\\Model\\ProductImage';
    const table = 'product_images';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'product_id',
      1 => 'title',
      2 => 'image',
      3 => 'large',
      4 => 'id',
    );
    public static $column_hash = array (
      'product_id' => 1,
      'title' => 1,
      'image' => 1,
      'large' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductImageSchemaProxy');
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
    public function getId()
    {
            return $this->get('id');
    }
}
