<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Collection;

class ProductSubsectionCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSubsectionSchemaProxy';

    const MODEL_CLASS = 'ProductBundle\\Model\\ProductSubsection';

    const TABLE = 'product_subsections';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductSubsectionRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductSubsectionSchemaProxy;
    }
}
