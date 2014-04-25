<?php
namespace ProductBundle\Model;

class ProductSubsectionBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'ProductBundle\\Model\\ProductSubsectionSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductSubsectionCollection';
const model_class = 'ProductBundle\\Model\\ProductSubsection';
const table = 'product_subsections';

public static $column_names = array (
  0 => 'title',
  1 => 'cover_image',
  2 => 'content',
  3 => 'product_id',
  4 => 'id',
);
public static $column_hash = array (
  'title' => 1,
  'cover_image' => 1,
  'content' => 1,
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
