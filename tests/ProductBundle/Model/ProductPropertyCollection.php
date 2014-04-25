<?php
namespace ProductBundle\Model;

class ProductPropertyCollection  extends \ProductBundle\Model\ProductPropertyCollectionBase {
    public $defaultOrdering = array(
        array('ordering','asc'),
        array('id','asc'),
    );
}
