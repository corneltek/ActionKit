<?php
namespace Product\Model;

class ProductTypeBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = '\\Product\\Model\\ProductTypeSchemaProxy';
const collection_class = '\\Product\\Model\\ProductTypeCollection';
const model_class = '\\Product\\Model\\ProductType';
const table = 'product_types';

}
