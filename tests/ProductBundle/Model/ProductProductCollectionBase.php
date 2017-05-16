<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class ProductProductCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductProductSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProduct';

    const TABLE = 'product_products';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductProductRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductProductSchemaProxy;
    }
}
