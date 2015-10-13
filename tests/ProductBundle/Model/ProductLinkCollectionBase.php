<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductLinkCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductLinkSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductLink';
    const TABLE = 'product_links';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
