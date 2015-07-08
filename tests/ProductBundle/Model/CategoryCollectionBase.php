<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class CategoryCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\CategorySchemaProxy';
    const model_class = 'ProductBundle\\Model\\Category';
    const table = 'product_categories';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
