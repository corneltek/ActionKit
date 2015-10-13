<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductCategorySchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductCategorySchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductCategoryCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductCategory';
    const model_name = 'ProductCategory';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_category_junction';
    const LABEL = 'ProductCategory';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'category_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'category_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'category_id',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'category_id',
    );
    public $primaryKey = 'id';
    public $table = 'product_category_junction';
    public $modelClass = 'ProductBundle\\Model\\ProductCategory';
    public $collectionClass = 'ProductBundle\\Model\\ProductCategoryCollection';
    public $label = 'ProductCategory';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'category' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductCategorySchema',
          'self_column' => 'category_id',
          'foreign_schema' => 'ProductBundle\\Model\\CategorySchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'category',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductCategorySchema',
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
        ),
      'name' => 'product_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
    ));
        $this->columns[ 'category_id' ] = new RuntimeColumn('category_id',array( 
      'locales' => NULL,
      'attributes' => array( 
        ),
      'name' => 'category_id',
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
