<?php
namespace OrderBundle\Model;
use LazyRecord\BaseCollection;
class OrderItemCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'OrderBundle\\Model\\OrderItemSchemaProxy';
    const model_class = 'OrderBundle\\Model\\OrderItem';
    const table = 'order_items';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
