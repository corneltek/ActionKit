<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ResourceCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ResourceSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\Resource';
    const TABLE = 'product_resources';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
