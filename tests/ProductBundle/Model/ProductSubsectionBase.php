<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace ProductBundle\Model;

use LazyRecord\BaseModel;

class ProductSubsectionBase  extends BaseModel {

    const schema_proxy_class = 'ProductBundle\\Model\\ProductSubsectionSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductSubsectionCollection';
    const model_class = 'ProductBundle\\Model\\ProductSubsection';
    const table = 'product_subsections';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


public static $column_names = array (
  0 => 'title',
  1 => 'cover_image',
  2 => 'content',
  3 => 'product_id',
  4 => 'id',
);
public static $column_hash = array (
  'title' => 1,
  'cover_image' => 1,
  'content' => 1,
  'product_id' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductSubsectionSchemaProxy');
}

    public function getTitle() {
    if (isset($this->_data['title'])) {
        return $this->_data['title'];
    }
}

    public function getCoverImage() {
    if (isset($this->_data['cover_image'])) {
        return $this->_data['cover_image'];
    }
}

    public function getContent() {
    if (isset($this->_data['content'])) {
        return $this->_data['content'];
    }
}

    public function getProductId() {
    if (isset($this->_data['product_id'])) {
        return $this->_data['product_id'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

