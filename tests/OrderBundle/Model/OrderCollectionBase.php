<?php
namespace OrderBundle\Model;

use Maghead\Runtime\Collection;

class OrderCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderSchemaProxy';

    const MODEL_CLASS = 'OrderBundle\\Model\\Order';

    const TABLE = 'orders';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \OrderBundle\Model\OrderRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \OrderBundle\Model\OrderSchemaProxy;
    }
}
