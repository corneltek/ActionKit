<?php
namespace ProductBundle\Model;

class ProductProductBase  extends \Phifty\Model {
const schema_proxy_class = 'ProductBundle\\Model\\ProductProductSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductProductCollection';
const model_class = 'ProductBundle\\Model\\ProductProduct';
const table = 'product_products';

public static $column_names = array (
  0 => 'product_id',
  1 => 'related_product_id',
  2 => 'ordering',
  3 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'related_product_id' => 1,
  'ordering' => 1,
  'id' => 1,
);
  0 => 'SortablePlugin\\Model\\Mixin\\OrderingSchema',
);



    /**
     * Code block for message id parser.
     */
    private function __() {
            }
}
