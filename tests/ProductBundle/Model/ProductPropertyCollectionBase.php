<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductPropertyCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductPropertySchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProperty';
    const TABLE = 'product_properties';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
