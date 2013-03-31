<?php
namespace Product\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductTypeSchemaProxy extends RuntimeSchema
{

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'label' => 'Product',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'refer' => 'Product\\Model\\Product',
        ),
    ),
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(120)',
          'isa' => 'str',
          'size' => 120,
          'required' => true,
          'label' => 'Name',
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
  'product_id',
  'name',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_types';
        $this->modelClass      = 'Product\\Model\\ProductType';
        $this->collectionClass = 'Product\\Model\\ProductTypeCollection';
        $this->label           = 'ProductType';
        $this->relations       = array( 
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
