<?php
namespace Kendo\Model;

use LazyRecord\Schema\RuntimeSchema;

class AccessResourceSchemaProxy extends RuntimeSchema
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
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
          'required' => true,
        ),
    ),
  'label' => array( 
      'name' => 'label',
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
  'name',
  'label',
  'description',
);
        $this->primaryKey      = 'id';
        $this->table           = 'access_resources';
        $this->modelClass      = 'Kendo\\Model\\AccessResource';
        $this->collectionClass = 'Kendo\\Model\\AccessResourceCollection';
        $this->label           = 'AccessResource';
        $this->relations       = array( 
  'access_rules' => array( 
      'type' => 2,
      'self' => array( 
          'column' => 'name',
          'schema' => 'Kendo\\Model\\AccessResourceSchema',
        ),
      'foreign' => array( 
          'column' => 'resource',
          'schema' => 'Kendo\\Model\\AccessRuleSchema',
        ),
    ),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
