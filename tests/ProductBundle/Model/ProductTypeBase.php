<?php
namespace ProductBundle\Model;

class ProductTypeBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\ProductTypeSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductTypeCollection';
const model_class = 'ProductBundle\\Model\\ProductType';
const table = 'product_types';

public static $column_names = array (
  0 => 'product_id',
  1 => 'name',
  2 => 'quantity',
  3 => 'comment',
  4 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'name' => 1,
  'quantity' => 1,
  'comment' => 1,
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
