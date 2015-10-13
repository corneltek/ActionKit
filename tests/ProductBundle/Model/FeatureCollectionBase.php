<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class FeatureCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\FeatureSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\Feature';
    const TABLE = 'product_features';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
