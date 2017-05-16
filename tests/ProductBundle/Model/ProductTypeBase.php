<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class ProductTypeBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductTypeSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductTypeSchema';

    const LABEL = '產品類型';

    const MODEL_NAME = 'ProductType';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductType';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductTypeRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductTypeCollection';

    const TABLE = 'product_types';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'name',
      3 => 'quantity',
      4 => 'comment',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_types';

    public $id;

    public $product_id;

    public $name;

    public $quantity;

    public $comment;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductTypeSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductTypeRepoBase($write, $read);
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

    public function getProductId()
    {
        return intval($this->product_id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getQuantity()
    {
        return intval($this->quantity);
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "name" => $this->name, "quantity" => $this->quantity, "comment" => $this->comment];
    }

    public function getData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "name" => $this->name, "quantity" => $this->quantity, "comment" => $this->comment];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
        if (array_key_exists("name", $data)) { $this->name = $data["name"]; }
        if (array_key_exists("quantity", $data)) { $this->quantity = $data["quantity"]; }
        if (array_key_exists("comment", $data)) { $this->comment = $data["comment"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->product_id = NULL;
        $this->name = NULL;
        $this->quantity = NULL;
        $this->comment = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }
}
