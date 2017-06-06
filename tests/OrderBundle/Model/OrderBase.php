<?php

namespace OrderBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use Magsql\Bind;
use Magsql\ArgumentArray;
use DateTime;
use Maghead\Runtime\ActionCreatorTrait;

class OrderBase
    extends Model
{

    use ActionCreatorTrait;

    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'OrderBundle\\Model\\OrderSchema';

    const LABEL = 'Order';

    const MODEL_NAME = 'Order';

    const MODEL_NAMESPACE = 'OrderBundle\\Model';

    const MODEL_CLASS = 'OrderBundle\\Model\\Order';

    const REPO_CLASS = 'OrderBundle\\Model\\OrderRepoBase';

    const COLLECTION_CLASS = 'OrderBundle\\Model\\OrderCollection';

    const TABLE = 'orders';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'sum',
      2 => 'qty',
      3 => 'amount',
      4 => 'updated_at',
      5 => 'created_at',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'orders';

    public $id;

    public $sum;

    public $qty;

    public $amount;

    public $updated_at;

    public $created_at;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \OrderBundle\Model\OrderSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \OrderBundle\Model\OrderRepoBase($write, $read);
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

    public function getSum()
    {
        return intval($this->sum);
    }

    public function getQty()
    {
        return intval($this->qty);
    }

    public function getAmount()
    {
        return intval($this->amount);
    }

    public function getUpdatedAt()
    {
        return Inflator::inflate($this->updated_at, 'DateTime');
    }

    public function getCreatedAt()
    {
        return Inflator::inflate($this->created_at, 'DateTime');
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "sum" => $this->sum, "qty" => $this->qty, "amount" => $this->amount, "updated_at" => $this->updated_at, "created_at" => $this->created_at];
    }

    public function getData()
    {
        return ["id" => $this->id, "sum" => $this->sum, "qty" => $this->qty, "amount" => $this->amount, "updated_at" => $this->updated_at, "created_at" => $this->created_at];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("sum", $data)) { $this->sum = $data["sum"]; }
        if (array_key_exists("qty", $data)) { $this->qty = $data["qty"]; }
        if (array_key_exists("amount", $data)) { $this->amount = $data["amount"]; }
        if (array_key_exists("updated_at", $data)) { $this->updated_at = $data["updated_at"]; }
        if (array_key_exists("created_at", $data)) { $this->created_at = $data["created_at"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->sum = NULL;
        $this->qty = NULL;
        $this->amount = NULL;
        $this->updated_at = NULL;
        $this->created_at = NULL;
    }
}
