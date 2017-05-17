<?php

namespace OrderBundle\Model;


use Maghead\Runtime\Collection;

class OrderItemCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderItemSchemaProxy';

    const MODEL_CLASS = 'OrderBundle\\Model\\OrderItem';

    const TABLE = 'order_items';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \OrderBundle\Model\OrderItemRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \OrderBundle\Model\OrderItemSchemaProxy;
    }
}
