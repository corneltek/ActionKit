<?php
namespace ProductBundle\Model;



class ProductCollection 
extends \ProductBundle\Model\ProductCollectionBase
{

    public static function getReadableItems()
    {
        $items = new self;
        $items->where()
            ->equal('status','publish');
        $items->order('created_on','desc');
        return $items;
    }

    public static function getCoverProducts() {
        $coverProducts = new self;
        $coverProducts->where(array(
            'is_cover' => true,
            'status' => 'publish'
        ));
        $coverProducts->order('created_on','desc');
        return $coverProducts;
    }
}
