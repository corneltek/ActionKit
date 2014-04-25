<?php
namespace ProductBundle\Model;

class ProductPropertyCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\ProductPropertySchemaProxy';
const model_class = '\\ProductBundle\\Model\\ProductProperty';
const table = 'product_properties';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
