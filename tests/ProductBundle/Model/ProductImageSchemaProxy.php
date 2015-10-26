<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductImageSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductImageSchema';
    const model_name = 'ProductImage';
    const model_namespace = 'ProductBundle\\Model';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductImageCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductImage';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_images';
    const LABEL = '產品圖';
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'title' => 1,
      'image' => 1,
      'large' => 1,
    );
    public static $mixin_classes = array (
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'image',
      4 => 'large',
    );
    public $primaryKey = 'id';
    public $columnNamesIncludeVirtual = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'image',
      4 => 'large',
    );
    public $label = '產品圖';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductImageSchema',
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
          'label' => '圖片標題',
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
      'label' => '圖片標題',
    ));
        $this->columns[ 'image' ] = new RuntimeColumn('image',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 130,
          'required' => true,
          'label' => '圖',
        ),
      'name' => 'image',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'length' => 130,
      'required' => true,
      'label' => '圖',
    ));
        $this->columns[ 'large' ] = new RuntimeColumn('large',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 130,
          'label' => '最大圖',
        ),
      'name' => 'large',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 130,
      'label' => '最大圖',
    ));
    }
}
