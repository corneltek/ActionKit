<?php
namespace ProductBundle\Model;

class FeatureBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\FeatureSchemaProxy';
const collection_class = 'ProductBundle\\Model\\FeatureCollection';
const model_class = 'ProductBundle\\Model\\Feature';
const table = 'product_features';

public static $column_names = array (
  0 => 'name',
  1 => 'description',
  2 => 'image',
  3 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'description' => 1,
  'image' => 1,
  'id' => 1,
);
public static $mixin_classes = array (
);



    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
