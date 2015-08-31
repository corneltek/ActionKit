<?php
namespace User\Model;
use LazyRecord\BaseModel;
class UserBase
    extends BaseModel
{
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
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('User\\Model\\UserSchemaProxy');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getEmail()
    {
            return $this->get('email');
    }
    public function getPassword()
    {
            return $this->get('password');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
