<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use Magsql\Bind;
use Magsql\ArgumentArray;
use DateTime;

class ProductFileBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductFileSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductFileSchema';

    const LABEL = '產品檔案';

    const MODEL_NAME = 'ProductFile';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFile';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductFileRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductFileCollection';

    const TABLE = 'product_files';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'title',
      3 => 'file',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_files';

    public $id;

    public $product_id;

    public $title;

    public $file;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductFileSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductFileRepoBase($write, $read);
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

    public function getFile()
    {
        return $this->file;
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "title" => $this->title, "file" => $this->file];
    }

    public function getData()
    {
        return ["id" => $this->id, "product_id" => $this->product_id, "title" => $this->title, "file" => $this->file];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("product_id", $data)) { $this->product_id = $data["product_id"]; }
        if (array_key_exists("title", $data)) { $this->title = $data["title"]; }
        if (array_key_exists("file", $data)) { $this->file = $data["file"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->product_id = NULL;
        $this->title = NULL;
        $this->file = NULL;
    }

    public function fetchProduct()
    {
        return static::masterRepo()->fetchProductOf($this);
    }
}
