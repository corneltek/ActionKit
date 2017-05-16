<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class CategoryBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\CategorySchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\CategorySchema';

    const LABEL = 'Category';

    const MODEL_NAME = 'Category';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\Category';

    const REPO_CLASS = 'ProductBundle\\Model\\CategoryRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\CategoryCollection';

    const TABLE = 'product_categories';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'parent_id',
      4 => 'hide',
      5 => 'thumb',
      6 => 'image',
      7 => 'handle',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_categories';

    public $id;

    public $name;

    public $description;

    public $parent_id;

    public $hide;

    public $thumb;

    public $image;

    public $handle;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\CategorySchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\CategoryRepoBase($write, $read);
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function getKey()
    {
        return $this->id;
    }

    public function hasKey()
    {
        return isset($this->id);
    }

    public function setKey($key)
    {
        return $this->id = $key;
    }

    public function removeLocalPrimaryKey()
    {
        $this->id = null;
    }

    public function getId()
    {
        return intval($this->id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getParentId()
    {
        return intval($this->parent_id);
    }

    public function isHide()
    {
        $value = $this->hide;
        if ($value === '' || $value === null) {
           return null;
        }
        return boolval($value);
    }

    public function getThumb()
    {
        return $this->thumb;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "name" => $this->name, "description" => $this->description, "parent_id" => $this->parent_id, "hide" => $this->hide, "thumb" => $this->thumb, "image" => $this->image, "handle" => $this->handle];
    }

    public function getData()
    {
        return ["id" => $this->id, "name" => $this->name, "description" => $this->description, "parent_id" => $this->parent_id, "hide" => $this->hide, "thumb" => $this->thumb, "image" => $this->image, "handle" => $this->handle];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("name", $data)) { $this->name = $data["name"]; }
        if (array_key_exists("description", $data)) { $this->description = $data["description"]; }
        if (array_key_exists("parent_id", $data)) { $this->parent_id = $data["parent_id"]; }
        if (array_key_exists("hide", $data)) { $this->hide = $data["hide"]; }
        if (array_key_exists("thumb", $data)) { $this->thumb = $data["thumb"]; }
        if (array_key_exists("image", $data)) { $this->image = $data["image"]; }
        if (array_key_exists("handle", $data)) { $this->handle = $data["handle"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->name = NULL;
        $this->description = NULL;
        $this->parent_id = NULL;
        $this->hide = NULL;
        $this->thumb = NULL;
        $this->image = NULL;
        $this->handle = NULL;
    }

    public function fetchSubcategories()
    {
        return static::masterRepo()->fetchSubcategoriesOf($this);
    }

    public function getSubcategories()
    {
        $collection = new \ProductBundle\Model\CategoryCollection;
        $collection->where()->equal("parent_id", $this->id);
        $collection->setPresetVars([ "parent_id" => $this->id ]);
        return $collection;
    }

    public function fetchParent()
    {
        return static::masterRepo()->fetchParentOf($this);
    }

    public function fetchCategoryProducts()
    {
        return static::masterRepo()->fetchCategoryProductsOf($this);
    }

    public function getCategoryProducts()
    {
        $collection = new \ProductBundle\Model\ProductCategoryCollection;
        $collection->where()->equal("category_id", $this->id);
        $collection->setPresetVars([ "category_id" => $this->id ]);
        return $collection;
    }

    public function getProducts()
    {
        $collection = new \ProductBundle\Model\ProductCollection;
        $collection->joinTable('product_category_junction', 'j', 'INNER')
           ->on("j.product_id = {$collection->getAlias()}.id");
        $collection->where()->equal('j.category_id', $this->id);
        $parent = $this;
        $collection->setAfterCreate(function($record, $args) use ($parent) {
           $a = [
              'product_id' => $record->get("id"),
              'category_id' => $parent->id,
           ];
           if (isset($args['category_products'])) {
              $a = array_merge($args['category_products'], $a);
           }
           return \ProductBundle\Model\ProductCategory::createAndLoad($a);
        });
        return $collection;
    }
}
