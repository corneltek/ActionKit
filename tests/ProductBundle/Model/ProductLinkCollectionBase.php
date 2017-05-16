<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class ProductLinkCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductLinkSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductLink';

    const TABLE = 'product_links';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductLinkRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductLinkSchemaProxy;
    }
}
