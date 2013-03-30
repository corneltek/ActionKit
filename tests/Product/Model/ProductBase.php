<?php
namespace Product\Model;

class ProductBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = '\\Product\\Model\\ProductSchemaProxy';
const collection_class = '\\Product\\Model\\ProductCollection';
const model_class = '\\Product\\Model\\Product';
const table = 'products';

}
