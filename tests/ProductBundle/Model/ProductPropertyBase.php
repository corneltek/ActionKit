<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace ProductBundle\Model;

use LazyRecord\BaseModel;

class ProductPropertyBase  extends BaseModel {

    const schema_proxy_class = 'ProductBundle\\Model\\ProductPropertySchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductPropertyCollection';
    const model_class = 'ProductBundle\\Model\\ProductProperty';
    const table = 'product_properties';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


public static $column_names = array (
  0 => 'name',
  1 => 'value',
  2 => 'product_id',
  3 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'value' => 1,
  'product_id' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductPropertySchemaProxy');
}

    public function getName() {
    if (isset($this->_data['name'])) {
        return $this->_data['name'];
    }
}

    public function getValue() {
    if (isset($this->_data['value'])) {
        return $this->_data['value'];
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

