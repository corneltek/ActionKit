<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductFeatureCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductFeatureSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFeature';
    const TABLE = 'product_feature_junction';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
