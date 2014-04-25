<?php
namespace ProductBundle\Model;

class ResourceBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\ResourceSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ResourceCollection';
const model_class = 'ProductBundle\\Model\\Resource';
const table = 'product_resources';

public static $column_names = array (
  0 => 'product_id',
  1 => 'url',
  2 => 'html',
  3 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'url' => 1,
  'html' => 1,
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
