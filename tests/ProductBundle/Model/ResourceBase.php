<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace ProductBundle\Model;

use LazyRecord\BaseModel;

class ResourceBase  extends BaseModel {

    const schema_proxy_class = 'ProductBundle\\Model\\ResourceSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ResourceCollection';
    const model_class = 'ProductBundle\\Model\\Resource';
    const table = 'product_resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


public static $column_names = array (
  0 => 'product_id',
  1 => 'url',
  2 => 'html',
  3 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'url' => 1,
  'html' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ResourceSchemaProxy');
}

    public function getProductId() {
    if (isset($this->_data['product_id'])) {
        return $this->_data['product_id'];
    }
}

    public function getUrl() {
    if (isset($this->_data['url'])) {
        return $this->_data['url'];
    }
}

    public function getHtml() {
    if (isset($this->_data['html'])) {
        return $this->_data['html'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

