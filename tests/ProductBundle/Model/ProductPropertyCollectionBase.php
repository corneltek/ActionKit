<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Collection;

class ProductPropertyCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductPropertySchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProperty';

    const TABLE = 'product_properties';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductPropertyRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductPropertySchemaProxy;
    }
}
