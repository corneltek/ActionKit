<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class ProductPropertyBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductPropertySchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductPropertySchema';

    const LABEL = 'ProductProperty';

    const MODEL_NAME = 'ProductProperty';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProperty';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductPropertyRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductPropertyCollection';

    const TABLE = 'product_properties';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'val',
      3 => 'product_id',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_properties';

    public $id;

    public $name;

    public $val;

    public $product_id;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductPropertySchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductPropertyRepoBase($write, $read);
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

    public function getVal()
    {
        return $this->val;
    }

    public function getProductId()
    {
        return intval($this->product_id);
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "name" => $this->name, "val" => $this->val, "product_id" => $this->product_id];
    }

    public function getData()
    {
        return ["id" => $this->id, "name" => $this->name, "val" => $this->val, "product_id" => $this->product_id];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("name", $data)) { $this->name = $data["name"]; }
        if (array_key_exists("val", $data)) { $this->val = $data["val"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->name = NULL;
        $this->val = NULL;
        $this->product_id = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }
}
