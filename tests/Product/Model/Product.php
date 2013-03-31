<?php
namespace Product\Model;

class Product  extends \Product\Model\ProductBase {

    public function dataLabel()
    {
        return $this->name;
    }

}
