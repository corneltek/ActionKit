<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace Product\Model;

use LazyRecord\BaseModel;

class ProductCategoryBase  extends BaseModel {

    const schema_proxy_class = 'Product\\Model\\ProductCategorySchemaProxy';
    const collection_class = 'Product\\Model\\ProductCategoryCollection';
    const model_class = 'Product\\Model\\ProductCategory';
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

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Product\\Model\\ProductCategorySchemaProxy');
}

    public function getProductId() {
    if (isset($this->_data['product_id'])) {
        return $this->_data['product_id'];
    }
}

    public function getCategoryId() {
    if (isset($this->_data['category_id'])) {
        return $this->_data['category_id'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

