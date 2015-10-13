<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductCategoryCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductCategorySchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductCategory';
    const TABLE = 'product_category_junction';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
