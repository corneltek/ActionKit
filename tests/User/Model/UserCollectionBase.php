<?php

namespace User\Model;


use Maghead\Runtime\Collection;

class UserCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'User\\Model\\UserSchemaProxy';

    const MODEL_CLASS = 'User\\Model\\User';

    const TABLE = 'users';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \User\Model\UserRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \User\Model\UserSchemaProxy;
    }
}
