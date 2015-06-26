<?php
/**
This is an auto-generated file,
Please DO NOT modify this file directly.
*/
namespace User\Model;

use LazyRecord\BaseModel;

class UserBase  extends BaseModel {

    const schema_proxy_class = 'User\\Model\\UserSchemaProxy';
    const collection_class = 'User\\Model\\UserCollection';
    const model_class = 'User\\Model\\User';
    const table = 'users';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';


    public static $column_names = array (
      0 => 'name',
      1 => 'email',
      2 => 'password',
      3 => 'id',
    );
    public static $column_hash = array (
      'name' => 1,
      'email' => 1,
      'password' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );

        public function getSchema() {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('User\\Model\\UserSchemaProxy');
    }

        public function getName() {
        if (isset($this->_data['name'])) {
            return $this->_data['name'];
        }
    }

        public function getEmail() {
        if (isset($this->_data['email'])) {
            return $this->_data['email'];
        }
    }

        public function getPassword() {
        if (isset($this->_data['password'])) {
            return $this->_data['password'];
        }
    }

        public function getId() {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }

}

