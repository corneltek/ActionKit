<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class ProductImageCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductImageSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductImage';

    const TABLE = 'product_images';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductImageRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductImageSchemaProxy;
    }
}
