<?php
namespace Order\Model;
use LazyRecord\BaseCollection;
class OrderCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Order\\Model\\OrderSchemaProxy';
    const model_class = 'Order\\Model\\Order';
    const table = 'orders';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
