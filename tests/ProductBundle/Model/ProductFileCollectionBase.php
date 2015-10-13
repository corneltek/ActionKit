<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductFileCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductFileSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductFile';
    const TABLE = 'product_files';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
