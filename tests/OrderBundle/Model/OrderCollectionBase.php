<?php
namespace OrderBundle\Model;
use LazyRecord\BaseCollection;
class OrderCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'OrderBundle\\Model\\OrderSchemaProxy';
    const model_class = 'OrderBundle\\Model\\Order';
    const table = 'orders';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
