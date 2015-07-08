<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductProductCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductProductSchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductProduct';
    const table = 'product_products';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
