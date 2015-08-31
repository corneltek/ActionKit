<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductCategoryBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductCategorySchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductCategoryCollection';
    const model_class = 'ProductBundle\\Model\\ProductCategory';
    const table = 'product_category_junction';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'product_id',
      1 => 'category_id',
      2 => 'id',
    );
    public static $column_hash = array (
      'product_id' => 1,
      'category_id' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductCategorySchemaProxy');
    }
    public function getProductId()
    {
            return $this->get('product_id');
    }
    public function getCategoryId()
    {
            return $this->get('category_id');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
