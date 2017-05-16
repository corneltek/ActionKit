<?php
namespace ProductBundle\Model;

use Maghead\Runtime\Collection;

class FeatureCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\FeatureSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\Feature';

    const TABLE = 'product_features';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\FeatureRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\FeatureSchemaProxy;
    }
}
