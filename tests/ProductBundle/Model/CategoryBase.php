<?php
namespace ProductBundle\Model;

class CategoryBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\CategorySchemaProxy';
const collection_class = 'ProductBundle\\Model\\CategoryCollection';
const model_class = 'ProductBundle\\Model\\Category';
const table = 'product_categories';

public static $column_names = array (
  0 => 'name',
  1 => 'description',
  2 => 'parent_id',
  3 => 'hide',
  4 => 'thumb',
  5 => 'image',
  6 => 'handle',
  7 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'description' => 1,
  'parent_id' => 1,
  'hide' => 1,
  'thumb' => 1,
  'image' => 1,
  'handle' => 1,
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
