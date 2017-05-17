<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class FeatureBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\FeatureSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\FeatureSchema';

    const LABEL = 'Feature';

    const MODEL_NAME = 'Feature';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\Feature';

    const REPO_CLASS = 'ProductBundle\\Model\\FeatureRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\FeatureCollection';

    const TABLE = 'product_features';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'description',
      3 => 'image',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'product_features';

    public $id;

    public $name;

    public $description;

    public $image;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\FeatureSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\FeatureRepoBase($write, $read);
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

    public function getImage()
    {
        return $this->image;
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "name" => $this->name, "description" => $this->description, "image" => $this->image];
    }

    public function getData()
    {
        return ["id" => $this->id, "name" => $this->name, "description" => $this->description, "image" => $this->image];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("name", $data)) { $this->name = $data["name"]; }
        if (array_key_exists("description", $data)) { $this->description = $data["description"]; }
        if (array_key_exists("image", $data)) { $this->image = $data["image"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->name = NULL;
        $this->description = NULL;
        $this->image = NULL;
    }
}
