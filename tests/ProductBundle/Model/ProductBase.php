<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace ProductBundle\Model;

use LazyRecord\BaseModel;

class ProductBase  extends BaseModel {

    const schema_proxy_class = 'ProductBundle\\Model\\ProductSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductCollection';
    const model_class = 'ProductBundle\\Model\\Product';
    const table = 'products';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


public static $column_names = array (
  0 => 'name',
  1 => 'subtitle',
  2 => 'sn',
  3 => 'description',
  4 => 'content',
  5 => 'category_id',
  6 => 'is_cover',
  7 => 'sellable',
  8 => 'orig_price',
  9 => 'price',
  10 => 'external_link',
  11 => 'token',
  12 => 'hide',
  13 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'subtitle' => 1,
  'sn' => 1,
  'description' => 1,
  'content' => 1,
  'category_id' => 1,
  'is_cover' => 1,
  'sellable' => 1,
  'orig_price' => 1,
  'price' => 1,
  'external_link' => 1,
  'token' => 1,
  'hide' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);

    public function getSchema() {
    if ($this->_schema) {
       return $this->_schema;
    }
    return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductSchemaProxy');
}

    public function getName() {
    if (isset($this->_data['name'])) {
        return $this->_data['name'];
    }
}

    public function getSubtitle() {
    if (isset($this->_data['subtitle'])) {
        return $this->_data['subtitle'];
    }
}

    public function getSn() {
    if (isset($this->_data['sn'])) {
        return $this->_data['sn'];
    }
}

    public function getDescription() {
    if (isset($this->_data['description'])) {
        return $this->_data['description'];
    }
}

    public function getContent() {
    if (isset($this->_data['content'])) {
        return $this->_data['content'];
    }
}

    public function getCategoryId() {
    if (isset($this->_data['category_id'])) {
        return $this->_data['category_id'];
    }
}

    public function getIsCover() {
    if (isset($this->_data['is_cover'])) {
        return $this->_data['is_cover'];
    }
}

    public function getSellable() {
    if (isset($this->_data['sellable'])) {
        return $this->_data['sellable'];
    }
}

    public function getOrigPrice() {
    if (isset($this->_data['orig_price'])) {
        return $this->_data['orig_price'];
    }
}

    public function getPrice() {
    if (isset($this->_data['price'])) {
        return $this->_data['price'];
    }
}

    public function getExternalLink() {
    if (isset($this->_data['external_link'])) {
        return $this->_data['external_link'];
    }
}

    public function getToken() {
    if (isset($this->_data['token'])) {
        return $this->_data['token'];
    }
}

    public function getHide() {
    if (isset($this->_data['hide'])) {
        return $this->_data['hide'];
    }
}

    public function getId() {
    if (isset($this->_data['id'])) {
        return $this->_data['id'];
    }
}

}

