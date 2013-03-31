<?php
namespace Product\Model;

class CategoryBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'Product\\Model\\CategorySchemaProxy';
const collection_class = 'Product\\Model\\CategoryCollection';
const model_class = 'Product\\Model\\Category';
const table = 'product_categories';

}
