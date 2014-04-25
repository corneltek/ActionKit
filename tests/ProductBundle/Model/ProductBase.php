<?php
namespace ProductBundle\Model;

class ProductBase  extends \Phifty\Model {
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
  5 => 'spec_content',
  6 => 'zoom_image',
  7 => 'category_id',
  8 => 'is_cover',
  9 => 'sellable',
  10 => 'orig_price',
  11 => 'price',
  12 => 'external_link',
  13 => 'token',
  14 => 'hide',
  15 => 'status',
  16 => 'lang',
  17 => 'thumb',
  18 => 'image',
  19 => 'created_on',
  20 => 'updated_on',
  21 => 'created_by',
  22 => 'updated_by',
  23 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'subtitle' => 1,
  'sn' => 1,
  'description' => 1,
  'content' => 1,
  'spec_content' => 1,
  'zoom_image' => 1,
  'category_id' => 1,
  'is_cover' => 1,
  'sellable' => 1,
  'orig_price' => 1,
  'price' => 1,
  'external_link' => 1,
  'token' => 1,
  'hide' => 1,
  'status' => 1,
  'lang' => 1,
  'thumb' => 1,
  'image' => 1,
  'created_on' => 1,
  'updated_on' => 1,
  'created_by' => 1,
  'updated_by' => 1,
  'id' => 1,
);
  0 => 'CommonBundle\\Model\\Mixin\\MetaSchema',
  1 => 'CommonBundle\\Model\\Mixin\\ImageSchema',
  2 => 'I18N\\Model\\Mixin\\I18NSchema',
  3 => 'StatusPlugin\\Model\\Mixin\\StatusSchema',
);



    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
