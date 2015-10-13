<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductProductSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductProductSchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductProductCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProduct';
    const model_name = 'ProductProduct';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_products';
    const LABEL = 'ProductProduct';
    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'related_product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'related_product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'related_product_id',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'related_product_id',
    );
    public $primaryKey = 'id';
    public $table = 'product_products';
    public $modelClass = 'ProductBundle\\Model\\ProductProduct';
    public $collectionClass = 'ProductBundle\\Model\\ProductProductCollection';
    public $label = 'ProductProduct';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductProductSchema',
          'self_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'product',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'related_product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductProductSchema',
          'self_column' => 'related_product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'related_product',
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
        $this->columns[ 'related_product_id' ] = new RuntimeColumn('related_product_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'refer' => 'ProductBundle\\Model\\Product',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'label' => '關連產品',
        ),
      'name' => 'related_product_id',
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
      'label' => '關連產品',
    ));
    }
}
