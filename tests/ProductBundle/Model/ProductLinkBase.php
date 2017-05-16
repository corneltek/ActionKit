<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class ProductLinkBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductLinkSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductLinkSchema';

    const LABEL = 'ProductLink';

    const MODEL_NAME = 'ProductLink';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductLink';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductLinkRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductLinkCollection';

    const TABLE = 'product_links';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'label',
      2 => 'url',
      3 => 'product_id',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_links';

    public $id;

    public $label;

    public $url;

    public $product_id;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductLinkSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductLinkRepoBase($write, $read);
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

    public function getLabel()
    {
        return $this->label;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getProductId()
    {
        return intval($this->product_id);
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "label" => $this->label, "url" => $this->url, "product_id" => $this->product_id];
    }

    public function getData()
    {
        return ["id" => $this->id, "label" => $this->label, "url" => $this->url, "product_id" => $this->product_id];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("label", $data)) { $this->label = $data["label"]; }
        if (array_key_exists("url", $data)) { $this->url = $data["url"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->label = NULL;
        $this->url = NULL;
        $this->product_id = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }
}
