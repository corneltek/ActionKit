<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductFeatureCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductFeatureSchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductFeature';
    const table = 'product_feature_junction';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
