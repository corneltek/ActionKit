<?php
namespace Product\Model;

class ProductBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'Product\\Model\\ProductSchemaProxy';
const collection_class = 'Product\\Model\\ProductCollection';
const model_class = 'Product\\Model\\Product';
const table = 'products';

public static $column_names = array (
  0 => 'name',
  1 => 'category_id',
  2 => 'cover_image',
  3 => 'id',
);
public static $column_hash = array (
  'name' => 1,
  'category_id' => 1,
  'cover_image' => 1,
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
