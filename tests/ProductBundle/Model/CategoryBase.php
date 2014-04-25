<?php
namespace ProductBundle\Model;

class CategoryBase  extends \Phifty\Model {
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
  7 => 'created_on',
  8 => 'updated_on',
  9 => 'created_by',
  10 => 'updated_by',
  11 => 'lang',
  12 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'description' => 1,
  'parent_id' => 1,
  'hide' => 1,
  'thumb' => 1,
  'image' => 1,
  'handle' => 1,
  'created_on' => 1,
  'updated_on' => 1,
  'created_by' => 1,
  'updated_by' => 1,
  'lang' => 1,
  'id' => 1,
);
  0 => 'I18N\\Model\\Mixin\\I18NSchema',
  1 => 'CommonBundle\\Model\\Mixin\\MetaSchema',
);



    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
