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

class ProductImageBase
    extends Model
{

    use ActionCreatorTrait;

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductImageSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductImageSchema';

    const LABEL = '產品圖';

    const MODEL_NAME = 'ProductImage';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductImage';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductImageRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductImageCollection';

    const TABLE = 'product_images';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'image',
      4 => 'large',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_images';

    public $id;

    public $product_id;

    public $title;

    public $image;

    public $large;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductImageSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductImageRepoBase($write, $read);
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getLarge()
    {
        return $this->large;
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "title" => $this->title, "image" => $this->image, "large" => $this->large];
    }

    public function getData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "title" => $this->title, "image" => $this->image, "large" => $this->large];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
        if (array_key_exists("title", $data)) { $this->title = $data["title"]; }
        if (array_key_exists("image", $data)) { $this->image = $data["image"]; }
        if (array_key_exists("large", $data)) { $this->large = $data["large"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->product_id = NULL;
        $this->title = NULL;
        $this->image = NULL;
        $this->large = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }
}
