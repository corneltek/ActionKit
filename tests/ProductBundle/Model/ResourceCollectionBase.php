<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ResourceCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ResourceSchemaProxy';
    const model_class = 'ProductBundle\\Model\\Resource';
    const table = 'product_resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
