<?php
namespace ProductBundle\Model;

class ProductImageBase  extends \Phifty\Model {
const schema_proxy_class = 'ProductBundle\\Model\\ProductImageSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductImageCollection';
const model_class = 'ProductBundle\\Model\\ProductImage';
const table = 'product_images';

public static $column_names = array (
  0 => 'product_id',
  1 => 'title',
  2 => 'large',
  3 => 'thumb',
  4 => 'image',
  5 => 'id',
  6 => 'ordering',
);
public static $column_hash = array (
  'product_id' => 1,
  'title' => 1,
  'large' => 1,
  'thumb' => 1,
  'image' => 1,
  'id' => 1,
  'ordering' => 1,
);
  0 => 'SortablePlugin\\Model\\Mixin\\OrderingSchema',
  1 => 'CommonBundle\\Model\\Mixin\\ImageSchema',
);

}
