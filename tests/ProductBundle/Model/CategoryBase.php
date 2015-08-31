<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class CategoryBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\CategorySchemaProxy';
    const collection_class = 'ProductBundle\\Model\\CategoryCollection';
    const model_class = 'ProductBundle\\Model\\Category';
    const table = 'product_categories';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'name',
      1 => 'description',
      2 => 'parent_id',
      3 => 'hide',
      4 => 'thumb',
      5 => 'image',
      6 => 'handle',
      7 => 'id',
    );
    public static $column_hash = array (
      'name' => 1,
      'description' => 1,
      'parent_id' => 1,
      'hide' => 1,
      'thumb' => 1,
      'image' => 1,
      'handle' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\CategorySchemaProxy');
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
    public function getId()
    {
            return $this->get('id');
    }
}
