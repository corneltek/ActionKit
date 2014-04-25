<?php
namespace ProductBundle\Model;

class ProductProductBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\ProductProductSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductProductCollection';
const model_class = 'ProductBundle\\Model\\ProductProduct';
const table = 'product_products';

public static $column_names = array (
  0 => 'product_id',
  1 => 'related_product_id',
  2 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'related_product_id' => 1,
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
