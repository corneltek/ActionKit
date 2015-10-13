<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductSubsectionCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSubsectionSchemaProxy';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductSubsection';
    const TABLE = 'product_subsections';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
