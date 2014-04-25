<?php
namespace ProductBundle\Model;

class ProductFileCollection  extends \ProductBundle\Model\ProductFileCollectionBase {

    public $defaultOrdering = array(
        array('ordering','asc'),
        array('id','asc'),
    );


}
