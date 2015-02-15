<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace Product\Model;

use LazyRecord\BaseModel;

class CategoryBase  extends BaseModel {

    const schema_proxy_class = 'Product\\Model\\CategorySchemaProxy';
    const collection_class = 'Product\\Model\\CategoryCollection';
    const model_class = 'Product\\Model\\Category';
    const table = 'product_categories';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


public static $column_names = array (
  0 => 'name',
  1 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Product\\Model\\CategorySchemaProxy');
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

