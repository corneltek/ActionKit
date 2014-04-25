<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaDeclare;

if( kernel()->bundle('RecipeBundle') ) {

class ProductRecipeSchema extends SchemaDeclare
{
    public function schema()
    {
        $this->column('product_id')->integer();
        $this->column('recipe_id')->integer();

        $this->belongsTo('recipe','RecipeBundle\\Model\\Recipe','id','recipe_id');
        $this->belongsTo('product','ProductBundle\\Model\\ProductSchema','id','product_id');
    }
}

}
