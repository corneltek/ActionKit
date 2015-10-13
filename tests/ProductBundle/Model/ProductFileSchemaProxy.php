<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductFileSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductFileSchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductFileCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFile';
    const model_name = 'ProductFile';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_files';
    const LABEL = '產品檔案';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'file',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'title' => 1,
      'file' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'file',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'file',
    );
    public $primaryKey = 'id';
    public $table = 'product_files';
    public $modelClass = 'ProductBundle\\Model\\ProductFile';
    public $collectionClass = 'ProductBundle\\Model\\ProductFileCollection';
    public $label = '產品檔案';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductFileSchema',
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
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
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
      'renderAs' => 'SelectInput',
      'widgetAttributes' => array( 
        ),
      'label' => '產品',
    ));
        $this->columns[ 'title' ] = new RuntimeColumn('title',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 130,
          'label' => '檔案標題',
        ),
      'name' => 'title',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 130,
      'label' => '檔案標題',
    ));
        $this->columns[ 'file' ] = new RuntimeColumn('file',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 130,
          'required' => true,
          'label' => '檔案',
        ),
      'name' => 'file',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'length' => 130,
      'required' => true,
      'label' => '檔案',
    ));
    }
}
