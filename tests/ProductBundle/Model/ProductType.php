<?php
namespace ProductBundle\Model;



class ProductType 
extends \ProductBundle\Model\ProductTypeBase
{

    public function dataLabel() {
        return $this->name;
    }

}
