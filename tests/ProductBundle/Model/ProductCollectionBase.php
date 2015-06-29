<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductSchemaProxy';
    const model_class = 'ProductBundle\\Model\\Product';
    const table = 'products';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
