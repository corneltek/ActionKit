<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class ResourceCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ResourceSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\Resource';

    const TABLE = 'product_resources';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ResourceRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ResourceSchemaProxy;
    }
}
