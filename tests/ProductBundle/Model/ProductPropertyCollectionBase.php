<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductPropertyCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductPropertySchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductProperty';
    const table = 'product_properties';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
