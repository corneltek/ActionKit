<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class ProductCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\Product';

    const TABLE = 'products';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductSchemaProxy;
    }
}
