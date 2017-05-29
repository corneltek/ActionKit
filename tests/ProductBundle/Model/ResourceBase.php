<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use Magsql\Bind;
use Magsql\ArgumentArray;
use DateTime;

class ResourceBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ResourceSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ResourceSchema';

    const LABEL = 'Resource';

    const MODEL_NAME = 'Resource';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\Resource';

    const REPO_CLASS = 'ProductBundle\\Model\\ResourceRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ResourceCollection';

    const TABLE = 'product_resources';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'url',
      3 => 'html',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_resources';

    public $id;

    public $product_id;

    public $url;

    public $html;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ResourceSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ResourceRepoBase($write, $read);
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

    public function getUrl()
    {
        return $this->url;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "url" => $this->url, "html" => $this->html];
    }

    public function getData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "url" => $this->url, "html" => $this->html];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
        if (array_key_exists("url", $data)) { $this->url = $data["url"]; }
        if (array_key_exists("html", $data)) { $this->html = $data["html"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->product_id = NULL;
        $this->url = NULL;
        $this->html = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }
}
