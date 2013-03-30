<?php
namespace Product\Model;

class CategoryCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\Product\\Model\\CategorySchemaProxy';
const model_class = '\\Product\\Model\\Category';
const table = 'product_categories';

}
