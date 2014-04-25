<?php
namespace ProductBundle\Model;

class ResourceCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\ResourceSchemaProxy';
const model_class = '\\ProductBundle\\Model\\Resource';
const table = 'product_resources';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
