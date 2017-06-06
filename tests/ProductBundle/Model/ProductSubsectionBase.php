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

class ProductSubsectionBase
    extends Model
{

    use ActionCreatorTrait;

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSubsectionSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductSubsectionSchema';

    const LABEL = 'ProductSubsection';

    const MODEL_NAME = 'ProductSubsection';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductSubsection';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductSubsectionRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductSubsectionCollection';

    const TABLE = 'product_subsections';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'title',
      2 => 'cover_image',
      3 => 'content',
      4 => 'product_id',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_subsections';

    public $id;

    public $title;

    public $cover_image;

    public $content;

    public $product_id;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductSubsectionSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductSubsectionRepoBase($write, $read);
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getCoverImage()
    {
        return $this->cover_image;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getProductId()
    {
        return intval($this->product_id);
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "title" => $this->title, "cover_image" => $this->cover_image, "content" => $this->content, "product_id" => $this->product_id];
    }

    public function getData()
    {
        return ["id" => $this->id, "title" => $this->title, "cover_image" => $this->cover_image, "content" => $this->content, "product_id" => $this->product_id];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("title", $data)) { $this->title = $data["title"]; }
        if (array_key_exists("cover_image", $data)) { $this->cover_image = $data["cover_image"]; }
        if (array_key_exists("content", $data)) { $this->content = $data["content"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->title = NULL;
        $this->cover_image = NULL;
        $this->content = NULL;
        $this->product_id = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }
}
