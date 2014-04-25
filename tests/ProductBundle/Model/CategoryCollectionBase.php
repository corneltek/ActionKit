<?php
namespace ProductBundle\Model;

class CategoryCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\CategorySchemaProxy';
const model_class = '\\ProductBundle\\Model\\Category';
const table = 'product_categories';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
