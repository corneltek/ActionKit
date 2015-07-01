<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductFileCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductFileSchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductFile';
    const table = 'product_files';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
