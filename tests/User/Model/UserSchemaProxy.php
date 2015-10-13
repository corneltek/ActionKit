<?php
namespace User\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class UserSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'User\\Model\\UserSchema';
    const COLLECTION_CLASS = 'User\\Model\\UserCollection';
    const MODEL_CLASS = 'User\\Model\\User';
    const model_name = 'User';
    const model_namespace = 'User\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'users';
    const LABEL = 'User';
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
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'name',
      2 => 'email',
      3 => 'password',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'name',
      2 => 'email',
      3 => 'password',
    );
    public $primaryKey = 'id';
    public $table = 'users';
    public $modelClass = 'User\\Model\\User';
    public $collectionClass = 'User\\Model\\UserCollection';
    public $label = 'User';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->columns[ 'id' ] = new RuntimeColumn('id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'autoIncrement' => true,
        ),
      'name' => 'id',
      'primary' => true,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'autoIncrement' => true,
    ));
        $this->columns[ 'name' ] = new RuntimeColumn('name',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 30,
        ),
      'name' => 'name',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 30,
    ));
        $this->columns[ 'email' ] = new RuntimeColumn('email',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
        ),
      'name' => 'email',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
    ));
        $this->columns[ 'password' ] = new RuntimeColumn('password',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
        ),
      'name' => 'password',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
    ));
    }
}
