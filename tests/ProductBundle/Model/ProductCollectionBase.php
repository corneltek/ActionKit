<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\Product';
    const TABLE = 'products';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
