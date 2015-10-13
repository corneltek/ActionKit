<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductProductCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductProductSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductProduct';
    const TABLE = 'product_products';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
