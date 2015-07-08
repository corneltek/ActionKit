<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductCategoryCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductCategorySchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductCategory';
    const table = 'product_category_junction';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
