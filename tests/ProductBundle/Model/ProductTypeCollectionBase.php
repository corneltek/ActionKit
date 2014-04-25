<?php
namespace ProductBundle\Model;

class ProductTypeCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\ProductTypeSchemaProxy';
const model_class = '\\ProductBundle\\Model\\ProductType';
const table = 'product_types';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
