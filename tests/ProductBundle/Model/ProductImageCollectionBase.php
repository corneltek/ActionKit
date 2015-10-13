<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductImageCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductImageSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductImage';
    const TABLE = 'product_images';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
