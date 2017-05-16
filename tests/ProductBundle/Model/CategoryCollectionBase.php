<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class CategoryCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\CategorySchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\Category';

    const TABLE = 'product_categories';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\CategoryRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\CategorySchemaProxy;
    }
}
