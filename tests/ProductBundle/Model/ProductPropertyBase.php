<?php
namespace ProductBundle\Model;

class ProductPropertyBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\ProductPropertySchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductPropertyCollection';
const model_class = 'ProductBundle\\Model\\ProductProperty';
const table = 'product_properties';

public static $column_names = array (
  0 => 'name',
  1 => 'value',
  2 => 'product_id',
  3 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'value' => 1,
  'product_id' => 1,
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
