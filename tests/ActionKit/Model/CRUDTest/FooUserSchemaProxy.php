<?php
namespace ActionKit\Model\CRUDTest;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class FooUserSchemaProxy extends RuntimeSchema
{

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
