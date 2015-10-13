<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductTypeCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductTypeSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductType';
    const TABLE = 'product_types';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
