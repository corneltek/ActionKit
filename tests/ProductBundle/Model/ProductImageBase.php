<?php
namespace ProductBundle\Model;

class ProductImageBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\ProductImageSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductImageCollection';
const model_class = 'ProductBundle\\Model\\ProductImage';
const table = 'product_images';

public static $column_names = array (
  0 => 'product_id',
  1 => 'title',
  2 => 'large',
  3 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'title' => 1,
  'large' => 1,
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
