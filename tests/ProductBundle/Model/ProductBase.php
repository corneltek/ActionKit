<?php
namespace ProductBundle\Model;

class ProductBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\ProductSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductCollection';
const model_class = 'ProductBundle\\Model\\Product';
const table = 'products';

public static $column_names = array (
  0 => 'name',
  1 => 'subtitle',
  2 => 'sn',
  3 => 'description',
  4 => 'content',
  5 => 'category_id',
  6 => 'is_cover',
  7 => 'sellable',
  8 => 'orig_price',
  9 => 'price',
  10 => 'external_link',
  11 => 'token',
  12 => 'hide',
  13 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'subtitle' => 1,
  'sn' => 1,
  'description' => 1,
  'content' => 1,
  'category_id' => 1,
  'is_cover' => 1,
  'sellable' => 1,
  'orig_price' => 1,
  'price' => 1,
  'external_link' => 1,
  'token' => 1,
  'hide' => 1,
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
