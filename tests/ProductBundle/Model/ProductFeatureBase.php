<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use Magsql\Bind;
use Magsql\ArgumentArray;
use DateTime;
use Maghead\Runtime\ActionCreatorTrait;

class ProductFeatureBase
    extends Model
{

    use ActionCreatorTrait;

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductFeatureSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductFeatureSchema';

    const LABEL = 'ProductFeature';

    const MODEL_NAME = 'ProductFeature';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFeature';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductFeatureRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductFeatureCollection';

    const TABLE = 'product_feature_junction';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'feature_id',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_feature_junction';

    public $id;

    public $product_id;

    public $feature_id;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductFeatureSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductFeatureRepoBase($write, $read);
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

    public function getFeatureId()
    {
        return intval($this->feature_id);
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "feature_id" => $this->feature_id];
    }

    public function getData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "feature_id" => $this->feature_id];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
        if (array_key_exists("feature_id", $data)) { $this->feature_id = $data["feature_id"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->product_id = NULL;
        $this->feature_id = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }

    public function fetchFeature()
    {
        return static::masterRepo()->fetchFeatureOf($this);
    }
}
