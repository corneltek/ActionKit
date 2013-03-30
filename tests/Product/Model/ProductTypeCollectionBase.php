<?php
namespace Product\Model;

class ProductTypeCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\Product\\Model\\ProductTypeSchemaProxy';
const model_class = '\\Product\\Model\\ProductType';
const table = 'product_types';

}
