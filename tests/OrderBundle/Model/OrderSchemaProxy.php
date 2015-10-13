<?php
namespace OrderBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class OrderSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'OrderBundle\\Model\\OrderSchema';
    const COLLECTION_CLASS = 'OrderBundle\\Model\\OrderCollection';
    const MODEL_CLASS = 'OrderBundle\\Model\\Order';
    const model_name = 'Order';
    const model_namespace = 'OrderBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'orders';
    const LABEL = 'Order';
    public static $column_names = array (
      0 => 'id',
      1 => 'sum',
      2 => 'qty',
    );
    public static $column_hash = array (
      'id' => 1,
      'sum' => 1,
      'qty' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'sum',
      2 => 'qty',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'sum',
      2 => 'qty',
    );
    public $primaryKey = 'id';
    public $table = 'orders';
    public $modelClass = 'OrderBundle\\Model\\Order';
    public $collectionClass = 'OrderBundle\\Model\\OrderCollection';
    public $label = 'Order';
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
        $this->columns[ 'sum' ] = new RuntimeColumn('sum',array( 
      'locales' => NULL,
      'attributes' => array( 
        ),
      'name' => 'sum',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
    ));
        $this->columns[ 'qty' ] = new RuntimeColumn('qty',array( 
      'locales' => NULL,
      'attributes' => array( 
        ),
      'name' => 'qty',
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
