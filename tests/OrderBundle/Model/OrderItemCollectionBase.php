<?php
namespace OrderBundle\Model;
use Maghead\Runtime\Collection;
class OrderItemCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'OrderBundle\\Model\\OrderItemSchemaProxy';
    const MODEL_CLASS = 'OrderBundle\\Model\\OrderItem';
    const TABLE = 'order_items';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
