<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Collection;

class ProductFeatureCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductFeatureSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFeature';

    const TABLE = 'product_feature_junction';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductFeatureRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductFeatureSchemaProxy;
    }
}
