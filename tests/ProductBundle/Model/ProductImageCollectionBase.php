<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductImageCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductImageSchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductImage';
    const table = 'product_images';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
