<?php
namespace ProductBundle\Model;

class ProductLinkCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\ProductLinkSchemaProxy';
const model_class = '\\ProductBundle\\Model\\ProductLink';
const table = 'product_links';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
