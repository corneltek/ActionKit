<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace Product\Model;

use LazyRecord\BaseModel;

class ProductTypeBase  extends BaseModel {

    const schema_proxy_class = 'Product\\Model\\ProductTypeSchemaProxy';
    const collection_class = 'Product\\Model\\ProductTypeCollection';
    const model_class = 'Product\\Model\\ProductType';
    const table = 'product_types';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


public static $column_names = array (
  0 => 'product_id',
  1 => 'name',
  2 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'name' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Product\\Model\\ProductTypeSchemaProxy');
}

    public function getProductId() {
    if (isset($this->_data['product_id'])) {
        return $this->_data['product_id'];
    }
}

    public function getName() {
    if (isset($this->_data['name'])) {
        return $this->_data['name'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

