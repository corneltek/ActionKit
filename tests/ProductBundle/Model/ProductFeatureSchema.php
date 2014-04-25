<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaDeclare;

class ProductFeatureSchema extends SchemaDeclare
{
    # feature relations
    public $table = 'product_feature_junction';

    function schema()
    {
        $this->column('product_id')->label( _('Product Id') )->refer( 'ProductBundle\\Model\\Product' );
        $this->column('feature_id')->label( _('Feature Id') )->refer( 'ProductBundle\\Model\\Feature' );
        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
        $this->belongsTo('feature','ProductBundle\\Model\\FeatureSchema','id','feature_id');
    }

}
