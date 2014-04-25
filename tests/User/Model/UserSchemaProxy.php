<?php
namespace User\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class UserSchemaProxy extends RuntimeSchema
{

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
    public static $column_names_include_virtual = array (
  0 => 'name',
  1 => 'email',
  2 => 'password',
  3 => 'id',
);

    const schema_class = 'User\\Model\\UserSchema';
    const collection_class = 'User\\Model\\UserCollection';
    const model_class = 'User\\Model\\User';
    const model_name = 'User';
    const model_namespace = 'User\\Model';
    const primary_key = 'id';
    const table = 'users';
    const label = 'User';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(30)',
          'isa' => 'str',
          'size' => 30,
        ),
    ),
  'email' => array( 
      'name' => 'email',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
        ),
    ),
  'password' => array( 
      'name' => 'password',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
        ),
    ),
  'id' => array( 
      'name' => 'id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'primary' => true,
          'autoIncrement' => true,
        ),
    ),
);
        $this->columnNames     = array( 
  'id',
  'name',
  'email',
  'password',
);
        $this->primaryKey      = 'id';
        $this->table           = 'users';
        $this->modelClass      = 'User\\Model\\User';
        $this->collectionClass = 'User\\Model\\UserCollection';
        $this->label           = 'User';
        $this->relations       = array( 
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

    /**
     * Code block for message id parser.
     */
    private function __() {
        _('User');
    }

}
