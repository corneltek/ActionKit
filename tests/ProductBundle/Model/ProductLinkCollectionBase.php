<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductLinkCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductLinkSchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductLink';
    const table = 'product_links';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
