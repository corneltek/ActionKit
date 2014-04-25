<?php
namespace ProductBundle\Model;



class ProductImageCollection 
extends \ProductBundle\Model\ProductImageCollectionBase
{
    public $defaultOrdering = array(
        array('ordering','asc'),
        array('id','asc'),
    );
}
