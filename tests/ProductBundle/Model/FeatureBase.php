<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace ProductBundle\Model;

use LazyRecord\BaseModel;

class FeatureBase  extends BaseModel {

    const schema_proxy_class = 'ProductBundle\\Model\\FeatureSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\FeatureCollection';
    const model_class = 'ProductBundle\\Model\\Feature';
    const table = 'product_features';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


public static $column_names = array (
  0 => 'name',
  1 => 'description',
  2 => 'image',
  3 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'description' => 1,
  'image' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\FeatureSchemaProxy');
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

    public function getImage() {
    if (isset($this->_data['image'])) {
        return $this->_data['image'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

