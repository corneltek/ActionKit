<?php
namespace ActionKit\Model\CRUDTest;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class FooUserSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'username',
  1 => 'password',
  2 => 'id',
);
    public static $column_hash = array (
  'username' => 1,
  'password' => 1,
  'id' => 1,
);
    public static $column_names_include_virtual = array (
  0 => 'username',
  1 => 'password',
  2 => 'id',
);

    const schema_class = 'LazyRecord\\Schema\\DynamicSchemaDeclare';
    const collection_class = 'ActionKit\\Model\\CRUDTest\\FooUserCollection';
    const model_class = 'ActionKit\\Model\\CRUDTest\\FooUser';
    const model_name = 'FooUser';
    const model_namespace = 'ActionKit\\Model\\CRUDTest';
    const primary_key = 'id';
    const table = 'foo_users';
    const label = 'FooUser';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'username' => array( 
      'name' => 'username',
      'attributes' => array( 
          'type' => 'varchar(12)',
          'isa' => 'str',
          'size' => 12,
        ),
    ),
  'password' => array( 
      'name' => 'password',
      'attributes' => array( 
          'type' => 'varchar(12)',
          'isa' => 'str',
          'size' => 12,
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
  'username',
  'password',
);
        $this->primaryKey      = 'id';
        $this->table           = 'foo_users';
        $this->modelClass      = 'ActionKit\\Model\\CRUDTest\\FooUser';
        $this->collectionClass = 'ActionKit\\Model\\CRUDTest\\FooUserCollection';
        $this->label           = 'FooUser';
        $this->relations       = array( 
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
