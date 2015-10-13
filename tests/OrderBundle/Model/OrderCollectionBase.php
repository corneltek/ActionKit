<?php
namespace OrderBundle\Model;
use LazyRecord\BaseCollection;
class OrderCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderSchemaProxy';
    const MODEL_CLASS = 'OrderBundle\\Model\\Order';
    const TABLE = 'orders';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
