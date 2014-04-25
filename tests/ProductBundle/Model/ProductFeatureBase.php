<?php
namespace ProductBundle\Model;

class ProductFeatureBase  extends \Phifty\Model {
const schema_proxy_class = 'ProductBundle\\Model\\ProductFeatureSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductFeatureCollection';
const model_class = 'ProductBundle\\Model\\ProductFeature';
const table = 'product_feature_junction';

public static $column_names = array (
  0 => 'product_id',
  1 => 'feature_id',
  2 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'feature_id' => 1,
  'id' => 1,
);
);

}
