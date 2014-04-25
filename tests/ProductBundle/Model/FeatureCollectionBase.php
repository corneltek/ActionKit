<?php
namespace ProductBundle\Model;

class FeatureCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\FeatureSchemaProxy';
const model_class = '\\ProductBundle\\Model\\Feature';
const table = 'product_features';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
