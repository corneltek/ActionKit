<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ResourceSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ResourceSchema';
    const model_name = 'Resource';
    const model_namespace = 'ProductBundle\\Model';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ResourceCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Resource';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_resources';
    const LABEL = 'Resource';
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'url' => 1,
      'html' => 1,
    );
    public static $mixin_classes = array (
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'url',
      3 => 'html',
    );
    public $primaryKey = 'id';
    public $columnNamesIncludeVirtual = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'url',
      3 => 'html',
    );
    public $label = 'Resource';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ResourceSchema',
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
        $this->columns[ 'product_id' ] = new RuntimeColumn('product_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'refer' => 'ProductBundle\\Model\\Product',
          'label' => '產品',
        ),
      'name' => 'product_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'refer' => 'ProductBundle\\Model\\Product',
      'label' => '產品',
    ));
        $this->columns[ 'url' ] = new RuntimeColumn('url',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 256,
          'label' => '網址',
        ),
      'name' => 'url',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 256,
      'label' => '網址',
    ));
        $this->columns[ 'html' ] = new RuntimeColumn('html',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 512,
          'label' => '內嵌 HTML',
          'renderAs' => 'TextareaInput',
          'widgetAttributes' => array( 
            ),
        ),
      'name' => 'html',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 512,
      'label' => '內嵌 HTML',
      'renderAs' => 'TextareaInput',
      'widgetAttributes' => array( 
        ),
    ));
    }
}
