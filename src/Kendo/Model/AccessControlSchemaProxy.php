<?php
namespace Kendo\Model;

use LazyRecord\Schema\RuntimeSchema;

class AccessControlSchemaProxy extends RuntimeSchema
{

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'role' => array( 
      'name' => 'role',
      'attributes' => array( 
          'type' => 'varchar(32)',
          'isa' => 'str',
          'size' => 32,
        ),
    ),
  'rule_id' => array( 
      'name' => 'rule_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'required' => true,
        ),
    ),
  'allow' => array( 
      'name' => 'allow',
      'attributes' => array( 
          'type' => 'boolean',
          'isa' => 'bool',
          'default' => false,
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
  'role',
  'rule_id',
  'allow',
);
        $this->primaryKey      = 'id';
        $this->table           = 'access_controls';
        $this->modelClass      = 'Kendo\\Model\\AccessControl';
        $this->collectionClass = 'Kendo\\Model\\AccessControlCollection';
        $this->label           = 'AccessControl';
        $this->relations       = array( 
  'rule' => array( 
      'type' => 4,
      'self' => array( 
          'schema' => 'Kendo\\Model\\AccessControlSchema',
          'column' => 'rule_id',
        ),
      'foreign' => array( 
          'schema' => 'Kendo\\Model\\AccessRuleSchema',
          'column' => 'id',
        ),
    ),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
