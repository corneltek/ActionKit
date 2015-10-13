<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductPropertySchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductPropertySchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductPropertyCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProperty';
    const model_name = 'ProductProperty';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_properties';
    const LABEL = 'ProductProperty';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'val',
      3 => 'product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'val' => 1,
      'product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'name',
      2 => 'val',
      3 => 'product_id',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'name',
      2 => 'val',
      3 => 'product_id',
    );
    public $primaryKey = 'id';
    public $table = 'product_properties';
    public $modelClass = 'ProductBundle\\Model\\ProductProperty';
    public $collectionClass = 'ProductBundle\\Model\\ProductPropertyCollection';
    public $label = 'ProductProperty';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductPropertySchema',
          'self_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'product',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
    );
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
        $this->columns[ 'name' ] = new RuntimeColumn('name',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 64,
        ),
      'name' => 'name',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 64,
    ));
        $this->columns[ 'val' ] = new RuntimeColumn('val',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 512,
        ),
      'name' => 'val',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 512,
    ));
        $this->columns[ 'product_id' ] = new RuntimeColumn('product_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'refer' => 'ProductBundle\\Model\\ProductSchema',
        ),
      'name' => 'product_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'refer' => 'ProductBundle\\Model\\ProductSchema',
    ));
    }
}
