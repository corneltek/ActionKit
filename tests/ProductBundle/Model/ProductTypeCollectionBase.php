<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class ProductTypeCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductTypeSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductType';

    const TABLE = 'product_types';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductTypeRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductTypeSchemaProxy;
    }
}
