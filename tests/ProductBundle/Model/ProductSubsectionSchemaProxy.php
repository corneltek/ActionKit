<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductSubsectionSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductSubsectionSchema';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductSubsectionCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductSubsection';
    const model_name = 'ProductSubsection';
    const model_namespace = 'ProductBundle\\Model';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_subsections';
    const LABEL = 'ProductSubsection';
    public static $column_names = array (
      0 => 'id',
      1 => 'title',
      2 => 'cover_image',
      3 => 'content',
      4 => 'product_id',
    );
    public static $column_hash = array (
      'id' => 1,
      'title' => 1,
      'cover_image' => 1,
      'content' => 1,
      'product_id' => 1,
    );
    public static $mixin_classes = array (
    );
    public static $column_names_include_virtual = array (
      0 => 'id',
      1 => 'title',
      2 => 'cover_image',
      3 => 'content',
      4 => 'product_id',
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'title',
      2 => 'cover_image',
      3 => 'content',
      4 => 'product_id',
    );
    public $primaryKey = 'id';
    public $table = 'product_subsections';
    public $modelClass = 'ProductBundle\\Model\\ProductSubsection';
    public $collectionClass = 'ProductBundle\\Model\\ProductSubsectionCollection';
    public $label = 'ProductSubsection';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductSubsectionSchema',
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
        $this->columns[ 'title' ] = new RuntimeColumn('title',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 64,
          'label' => '子區塊標題',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 50,
            ),
        ),
      'name' => 'title',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 64,
      'label' => '子區塊標題',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
          'size' => 50,
        ),
    ));
        $this->columns[ 'cover_image' ] = new RuntimeColumn('cover_image',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 64,
          'label' => '子區塊封面圖',
          'renderAs' => 'ThumbImageFileInput',
          'widgetAttributes' => array( 
            ),
        ),
      'name' => 'cover_image',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 64,
      'label' => '子區塊封面圖',
      'renderAs' => 'ThumbImageFileInput',
      'widgetAttributes' => array( 
        ),
    ));
        $this->columns[ 'content' ] = new RuntimeColumn('content',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => '子區塊內文',
          'renderAs' => 'TextareaInput',
          'widgetAttributes' => array( 
              'class' => '+=mceEditor',
              'rows' => 5,
              'cols' => 50,
            ),
        ),
      'name' => 'content',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'text',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => '子區塊內文',
      'renderAs' => 'TextareaInput',
      'widgetAttributes' => array( 
          'class' => '+=mceEditor',
          'rows' => 5,
          'cols' => 50,
        ),
    ));
        $this->columns[ 'product_id' ] = new RuntimeColumn('product_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'refer' => 'ProductBundle\\Model\\Product',
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
    ));
    }
}
