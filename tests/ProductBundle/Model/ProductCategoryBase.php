<?php
namespace ProductBundle\Model;

class ProductCategoryBase  extends \Phifty\Model {
const schema_proxy_class = 'ProductBundle\\Model\\ProductCategorySchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductCategoryCollection';
const model_class = 'ProductBundle\\Model\\ProductCategory';
const table = 'product_category_junction';

public static $column_names = array (
  0 => 'product_id',
  1 => 'category_id',
  2 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'category_id' => 1,
  'id' => 1,
);
);

}
