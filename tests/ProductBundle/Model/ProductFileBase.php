<?php
namespace ProductBundle\Model;

class ProductFileBase  extends \Phifty\Model {
const schema_proxy_class = 'ProductBundle\\Model\\ProductFileSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductFileCollection';
const model_class = 'ProductBundle\\Model\\ProductFile';
const table = 'product_files';

public static $column_names = array (
  0 => 'product_id',
  1 => 'title',
  2 => 'mimetype',
  3 => 'file',
  4 => 'ordering',
  5 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'title' => 1,
  'mimetype' => 1,
  'file' => 1,
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
