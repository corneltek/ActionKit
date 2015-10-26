<?php
namespace User\Model;
use LazyRecord\Schema\SchemaLoader;
use LazyRecord\Result;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use PDO;
use SQLBuilder\Universal\Query\InsertQuery;
use LazyRecord\BaseModel;
class UserBase
    extends BaseModel
{
    const SCHEMA_PROXY_CLASS = 'User\\Model\\UserSchemaProxy';
    const COLLECTION_CLASS = 'User\\Model\\UserCollection';
    const MODEL_CLASS = 'User\\Model\\User';
    const TABLE = 'users';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM users WHERE id = :id LIMIT 1';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'email',
      3 => 'password',
    );
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'email' => 1,
      'password' => 1,
    );
    public static $mixin_classes = array (
    );
    protected $table = 'users';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = SchemaLoader::load('User\\Model\\UserSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
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
}
