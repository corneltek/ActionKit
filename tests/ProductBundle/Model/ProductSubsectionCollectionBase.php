<?php
namespace ProductBundle\Model;
use LazyRecord\BaseCollection;
class ProductSubsectionCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductSubsectionSchemaProxy';
    const model_class = 'ProductBundle\\Model\\ProductSubsection';
    const table = 'product_subsections';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
