<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace ProductBundle\Model;

use LazyRecord\BaseModel;

class ProductImageBase  extends BaseModel {

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
  2 => 'large',
  3 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'title' => 1,
  'large' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductImageSchemaProxy');
}

    public function getProductId() {
    if (isset($this->_data['product_id'])) {
        return $this->_data['product_id'];
    }
}

    public function getTitle() {
    if (isset($this->_data['title'])) {
        return $this->_data['title'];
    }
}

    public function getLarge() {
    if (isset($this->_data['large'])) {
        return $this->_data['large'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

