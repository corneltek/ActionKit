<?php
namespace Product\Model;

class ProductCategoryBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = '\\Product\\Model\\ProductCategorySchemaProxy';
const collection_class = '\\Product\\Model\\ProductCategoryCollection';
const model_class = '\\Product\\Model\\ProductCategory';
const table = 'product_category_junction';

}
