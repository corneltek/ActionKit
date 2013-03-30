<?php
namespace Product\Model;

class ProductCategoryCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\Product\\Model\\ProductCategorySchemaProxy';
const model_class = '\\Product\\Model\\ProductCategory';
const table = 'product_category_junction';

}
