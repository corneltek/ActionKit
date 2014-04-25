<?php
namespace ProductBundle\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class ProductLinkSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'label',
  1 => 'url',
  2 => 'product_id',
  3 => 'id',
);
    public static $column_hash = array (
  'label' => 1,
  'url' => 1,
  'product_id' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'label',
  1 => 'url',
  2 => 'product_id',
  3 => 'id',
);

    const schema_class = 'ProductBundle\\Model\\ProductLinkSchema';
    const collection_class = 'ProductBundle\\Model\\ProductLinkCollection';
    const model_class = 'ProductBundle\\Model\\ProductLink';
    const model_name = 'ProductLink';
    const model_namespace = 'ProductBundle\\Model';
    const primary_key = 'id';
    const table = 'product_links';
    const label = 'ProductLink';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'label' => array( 
      'name' => 'label',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
        ),
    ),
  'url' => array( 
      'name' => 'url',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
        ),
    ),
  'product_id' => array( 
      'name' => 'product_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'refer' => 'ProductBundle\\Model\\ProductSchema',
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
  'label',
  'url',
  'product_id',
);
        $this->primaryKey      = 'id';
        $this->table           = 'product_links';
        $this->modelClass      = 'ProductBundle\\Model\\ProductLink';
        $this->collectionClass = 'ProductBundle\\Model\\ProductLinkCollection';
        $this->label           = 'ProductLink';
        $this->relations       = array( 
  'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'ProductBundle\\Model\\ProductLinkSchema',
      'self_column' => 'product_id',
      'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
      'foreign_column' => 'id',
    ),
)),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

    /**
     * Code block for message id parser.
     */
    private function __() {
        _('ProductLink');
    }

}
