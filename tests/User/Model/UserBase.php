<?php
namespace User\Model;

use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use DateTime;

class UserBase
    extends Model
{

    const SCHEMA_PROXY_CLASS = 'User\\Model\\UserSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'User\\Model\\UserSchema';

    const LABEL = 'User';

    const MODEL_NAME = 'User';

    const MODEL_NAMESPACE = 'User\\Model';

    const MODEL_CLASS = 'User\\Model\\User';

    const REPO_CLASS = 'User\\Model\\UserRepoBase';

    const COLLECTION_CLASS = 'User\\Model\\UserCollection';

    const TABLE = 'users';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'email',
      3 => 'password',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'users';

    public $id;

    public $name;

    public $email;

    public $password;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \User\Model\UserSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \User\Model\UserRepoBase($write, $read);
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "name" => $this->name, "email" => $this->email, "password" => $this->password];
    }

    public function getData()
    {
        return ["id" => $this->id, "name" => $this->name, "email" => $this->email, "password" => $this->password];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("name", $data)) { $this->name = $data["name"]; }
        if (array_key_exists("email", $data)) { $this->email = $data["email"]; }
        if (array_key_exists("password", $data)) { $this->password = $data["password"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->name = NULL;
        $this->email = NULL;
        $this->password = NULL;
    }
}
