<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class CategoryCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\CategorySchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\Category';
    const TABLE = 'product_categories';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
