<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class ProductCategoryCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductCategorySchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductCategory';

    const TABLE = 'product_category_junction';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductCategoryRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductCategorySchemaProxy;
    }
}
