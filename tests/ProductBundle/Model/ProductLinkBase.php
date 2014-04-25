<?php
namespace ProductBundle\Model;

class ProductLinkBase  extends \Phifty\Model {
const schema_proxy_class = 'ProductBundle\\Model\\ProductLinkSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductLinkCollection';
const model_class = 'ProductBundle\\Model\\ProductLink';
const table = 'product_links';

public static $column_names = array (
  0 => 'label',
  1 => 'url',
  2 => 'product_id',
  3 => 'ordering',
  4 => 'id',
);
public static $column_hash = array (
  'label' => 1,
  'url' => 1,
  'product_id' => 1,
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
