<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductLinkSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductLinkSchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductLinkCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductLink';
    const model_name = 'ProductLink';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_links';
    const LABEL = 'ProductLink';
    public static $column_names = array (
      0 => 'id',
      1 => 'label',
      2 => 'url',
      3 => 'product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'label' => 1,
      'url' => 1,
      'product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'label',
      2 => 'url',
      3 => 'product_id',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'label',
      2 => 'url',
      3 => 'product_id',
    );
    public $primaryKey = 'id';
    public $table = 'product_links';
    public $modelClass = 'ProductBundle\\Model\\ProductLink';
    public $collectionClass = 'ProductBundle\\Model\\ProductLinkCollection';
    public $label = 'ProductLink';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductLinkSchema',
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
        $this->columns[ 'label' ] = new RuntimeColumn('label',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
        ),
      'name' => 'label',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
    ));
        $this->columns[ 'url' ] = new RuntimeColumn('url',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
        ),
      'name' => 'url',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
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
