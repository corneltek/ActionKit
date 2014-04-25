<?php
namespace ProductBundle\Model;

class ProductUseCaseBase  extends \Phifty\Model {
const schema_proxy_class = 'ProductBundle\\Model\\ProductUseCaseSchemaProxy';
const collection_class = 'ProductBundle\\Model\\ProductUseCaseCollection';
const model_class = 'ProductBundle\\Model\\ProductUseCase';
const table = 'product_use_cases';

public static $column_names = array (
  0 => 'product_id',
  1 => 'usecase_id',
  2 => 'ordering',
  3 => 'id',
);
public static $column_hash = array (
  'product_id' => 1,
  'usecase_id' => 1,
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
