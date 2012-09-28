<?php
namespace Kendo\Model;

use LazyRecord\Schema\RuntimeSchema;

class AccessRuleSchemaProxy extends RuntimeSchema
{

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'rules_class' => array( 
      'name' => 'rules_class',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
        ),
    ),
  'resource' => array( 
      'name' => 'resource',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
          'required' => true,
        ),
    ),
  'operation' => array( 
      'name' => 'operation',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
          'required' => true,
        ),
    ),
  'operation_label' => array( 
      'name' => 'operation_label',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
        ),
    ),
  'description' => array( 
      'name' => 'description',
      'attributes' => array( 
          'type' => 'text',
          'isa' => 'str',
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
  'rules_class',
  'resource',
  'operation',
  'operation_label',
  'description',
);
        $this->primaryKey      = 'id';
        $this->table           = 'access_rules';
        $this->modelClass      = 'Kendo\\Model\\AccessRule';
        $this->collectionClass = 'Kendo\\Model\\AccessRuleCollection';
        $this->label           = 'AccessRule';
        $this->relations       = array( 
  'control' => array( 
      'type' => 4,
      'self' => array( 
          'schema' => 'Kendo\\Model\\AccessRuleSchema',
          'column' => 'id',
        ),
      'foreign' => array( 
          'schema' => 'Kendo\\Model\\AccessControlSchema',
          'column' => 'rule_id',
        ),
    ),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
