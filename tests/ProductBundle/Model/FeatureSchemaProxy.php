<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class FeatureSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\FeatureSchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\FeatureCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Feature';
    const model_name = 'Feature';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_features';
    const LABEL = 'Feature';
    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'image',
    );
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'description' => 1,
      'image' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'image',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'image',
    );
    public $primaryKey = 'id';
    public $table = 'product_features';
    public $modelClass = 'ProductBundle\\Model\\Feature';
    public $collectionClass = 'ProductBundle\\Model\\FeatureCollection';
    public $label = 'Feature';
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
        $this->columns[ 'name' ] = new RuntimeColumn('name',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
          'label' => '產品功能名稱',
        ),
      'name' => 'name',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
      'label' => '產品功能名稱',
    ));
        $this->columns[ 'description' ] = new RuntimeColumn('description',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => 'Description',
        ),
      'name' => 'description',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'text',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => 'Description',
    ));
        $this->columns[ 'image' ] = new RuntimeColumn('image',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 250,
          'label' => '產品功能圖示',
        ),
      'name' => 'image',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 250,
      'label' => '產品功能圖示',
    ));
    }
}
