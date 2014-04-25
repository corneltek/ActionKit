<?php
namespace Product\Model;

class ProductCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\Product\\Model\\ProductSchemaProxy';
const model_class = '\\Product\\Model\\Product';
const table = 'products';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
