<?php
namespace ProductBundle\Model;

class ProductFeatureCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\ProductBundle\\Model\\ProductFeatureSchemaProxy';
const model_class = '\\ProductBundle\\Model\\ProductFeature';
const table = 'product_feature_junction';




    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
