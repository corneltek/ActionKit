<?php
namespace ProductBundle\Model;

class ProductCategoryCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\ProductCategorySchemaProxy';
const model_class = '\\ProductBundle\\Model\\ProductCategory';
const table = 'product_category_junction';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
