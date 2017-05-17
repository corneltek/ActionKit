<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class ProductProductBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductProductSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductProductSchema';

    const LABEL = 'ProductProduct';

    const MODEL_NAME = 'ProductProduct';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProduct';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductProductRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductProductCollection';

    const TABLE = 'product_products';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'related_product_id',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_products';

    public $id;

    public $product_id;

    public $related_product_id;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductProductSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductProductRepoBase($write, $read);
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

    public function getRelatedProductId()
    {
        return intval($this->related_product_id);
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "related_product_id" => $this->related_product_id];
    }

    public function getData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "related_product_id" => $this->related_product_id];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
        if (array_key_exists("related_product_id", $data)) { $this->related_product_id = $data["related_product_id"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->product_id = NULL;
        $this->related_product_id = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }

    public function fetchRelatedProduct()
    {
        return static::masterRepo()->fetchRelatedProductOf($this);
    }
}
