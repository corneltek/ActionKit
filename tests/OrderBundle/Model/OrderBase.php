<?php
namespace OrderBundle\Model;

use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class OrderBase
    extends Model
{

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
    );

    public static $mixin_classes = array (
    );

    protected $table = 'orders';

    public $id;

    public $sum;

    public $qty;

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

    public function getAlterableData()
    {
        return ["id" => $this->id, "sum" => $this->sum, "qty" => $this->qty];
    }

    public function getData()
    {
        return ["id" => $this->id, "sum" => $this->sum, "qty" => $this->qty];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("sum", $data)) { $this->sum = $data["sum"]; }
        if (array_key_exists("qty", $data)) { $this->qty = $data["qty"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->sum = NULL;
        $this->qty = NULL;
    }
}
