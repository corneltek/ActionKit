<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace ProductBundle\Model;

use LazyRecord\BaseModel;

class CategoryBase  extends BaseModel {

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

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\CategorySchemaProxy');
}

    public function getName() {
    if (isset($this->_data['name'])) {
        return $this->_data['name'];
    }
}

    public function getDescription() {
    if (isset($this->_data['description'])) {
        return $this->_data['description'];
    }
}

    public function getParentId() {
    if (isset($this->_data['parent_id'])) {
        return $this->_data['parent_id'];
    }
}

    public function getHide() {
    if (isset($this->_data['hide'])) {
        return $this->_data['hide'];
    }
}

    public function getThumb() {
    if (isset($this->_data['thumb'])) {
        return $this->_data['thumb'];
    }
}

    public function getImage() {
    if (isset($this->_data['image'])) {
        return $this->_data['image'];
    }
}

    public function getHandle() {
    if (isset($this->_data['handle'])) {
        return $this->_data['handle'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

