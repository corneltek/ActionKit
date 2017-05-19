<?php

namespace OrderBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use Magsql\Bind;
use Magsql\ArgumentArray;
use DateTime;

class OrderItemBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderItemSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'OrderBundle\\Model\\OrderItemSchema';

    const LABEL = 'OrderItem';

    const MODEL_NAME = 'OrderItem';

    const MODEL_NAMESPACE = 'OrderBundle\\Model';

    const MODEL_CLASS = 'OrderBundle\\Model\\OrderItem';

    const REPO_CLASS = 'OrderBundle\\Model\\OrderItemRepoBase';

    const COLLECTION_CLASS = 'OrderBundle\\Model\\OrderItemCollection';

    const TABLE = 'order_items';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'quantity',
      2 => 'subtotal',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'order_items';

    public $id;

    public $quantity;

    public $subtotal;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \OrderBundle\Model\OrderItemSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \OrderBundle\Model\OrderItemRepoBase($write, $read);
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

    public function getQuantity()
    {
        return intval($this->quantity);
    }

    public function getSubtotal()
    {
        return intval($this->subtotal);
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "quantity" => $this->quantity, "subtotal" => $this->subtotal];
    }

    public function getData()
    {
        return ["id" => $this->id, "quantity" => $this->quantity, "subtotal" => $this->subtotal];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("quantity", $data)) { $this->quantity = $data["quantity"]; }
        if (array_key_exists("subtotal", $data)) { $this->subtotal = $data["subtotal"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->quantity = NULL;
        $this->subtotal = NULL;
    }
}
