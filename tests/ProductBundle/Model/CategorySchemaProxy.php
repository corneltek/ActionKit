<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class CategorySchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\CategorySchema';
    const model_name = 'Category';
    const model_namespace = 'ProductBundle\\Model';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\CategoryCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\Category';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_categories';
    const LABEL = 'Category';
    public static $column_hash = array (
      'id' => 1,
      'name' => 1,
      'description' => 1,
      'parent_id' => 1,
      'hide' => 1,
      'thumb' => 1,
      'image' => 1,
      'handle' => 1,
    );
    public static $mixin_classes = array (
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'parent_id',
      4 => 'hide',
      5 => 'thumb',
      6 => 'image',
      7 => 'handle',
    );
    public $primaryKey = 'id';
    public $columnNamesIncludeVirtual = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'parent_id',
      4 => 'hide',
      5 => 'thumb',
      6 => 'image',
      7 => 'handle',
    );
    public $label = 'Category';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'subcategories' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\CategorySchema',
          'foreign_column' => 'parent_id',
          'foreign_schema' => 'ProductBundle\\Model\\CategorySchema',
        ),
      'accessor' => 'subcategories',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'parent' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\CategorySchema',
          'self_column' => 'parent_id',
          'foreign_schema' => 'ProductBundle\\Model\\CategorySchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'parent',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'category_products' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 1,
          'self_column' => 'id',
          'self_schema' => 'ProductBundle\\Model\\CategorySchema',
          'foreign_column' => 'category_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductCategorySchema',
        ),
      'accessor' => 'category_products',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
      'products' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 4,
          'relation_junction' => 'category_products',
          'relation_foreign' => 'product',
        ),
      'accessor' => 'products',
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
          'length' => 130,
          'label' => '產品類別名稱',
          'required' => true,
        ),
      'name' => 'name',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => 1,
      'enum' => NULL,
      'set' => NULL,
      'length' => 130,
      'label' => '產品類別名稱',
      'required' => true,
    ));
        $this->columns[ 'description' ] = new RuntimeColumn('description',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => '產品類別敘述',
          'renderAs' => 'TextareaInput',
          'widgetAttributes' => array( 
              'class' => '+=mceEditor',
            ),
        ),
      'name' => 'description',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'text',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => '產品類別敘述',
      'renderAs' => 'TextareaInput',
      'widgetAttributes' => array( 
          'class' => '+=mceEditor',
        ),
    ));
        $this->columns[ 'parent_id' ] = new RuntimeColumn('parent_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'refer' => 'ProductBundle\\Model\\CategorySchema',
          'label' => '父類別',
          'default' => 0,
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
              'allow_empty' => 0,
            ),
        ),
      'name' => 'parent_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'refer' => 'ProductBundle\\Model\\CategorySchema',
      'label' => '父類別',
      'default' => 0,
      'renderAs' => 'SelectInput',
      'widgetAttributes' => array( 
          'allow_empty' => 0,
        ),
    ));
        $this->columns[ 'hide' ] = new RuntimeColumn('hide',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => '隱藏這個類別',
        ),
      'name' => 'hide',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'boolean',
      'isa' => 'bool',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => '隱藏這個類別',
    ));
        $this->columns[ 'thumb' ] = new RuntimeColumn('thumb',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
          'label' => '縮圖',
        ),
      'name' => 'thumb',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
      'label' => '縮圖',
    ));
        $this->columns[ 'image' ] = new RuntimeColumn('image',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 128,
          'label' => '圖片',
        ),
      'name' => 'image',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 128,
      'label' => '圖片',
    ));
        $this->columns[ 'handle' ] = new RuntimeColumn('handle',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 32,
          'label' => '程式用操作碼',
        ),
      'name' => 'handle',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'length' => 32,
      'label' => '程式用操作碼',
    ));
    }
}
