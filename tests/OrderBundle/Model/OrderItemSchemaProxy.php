<?php
namespace OrderBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class OrderItemSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'OrderBundle\\Model\\OrderItemSchema';
    const model_name = 'OrderItem';
    const model_namespace = 'OrderBundle\\Model';
    const COLLECTION_CLASS = 'OrderBundle\\Model\\OrderItemCollection';
    const MODEL_CLASS = 'OrderBundle\\Model\\OrderItem';
    const PRIMARY_KEY = 'id';
    const TABLE = 'order_items';
    const LABEL = 'OrderItem';
    public static $column_hash = array (
      'id' => 1,
      'quantity' => 1,
      'subtotal' => 1,
    );
    public static $mixin_classes = array (
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'quantity',
      2 => 'subtotal',
    );
    public $primaryKey = 'id';
    public $columnNamesIncludeVirtual = array (
      0 => 'id',
      1 => 'quantity',
      2 => 'subtotal',
    );
    public $label = 'OrderItem';
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
        $this->columns[ 'quantity' ] = new RuntimeColumn('quantity',array( 
      'locales' => NULL,
      'attributes' => array( 
          'required' => true,
        ),
      'name' => 'quantity',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'required' => true,
    ));
        $this->columns[ 'subtotal' ] = new RuntimeColumn('subtotal',array( 
      'locales' => NULL,
      'attributes' => array( 
        ),
      'name' => 'subtotal',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
    ));
    }
}
