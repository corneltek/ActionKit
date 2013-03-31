<?php
namespace User\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class UserSchemaProxy extends RuntimeSchema
{

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

}
