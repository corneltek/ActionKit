<?php
namespace Product\Model;

class Category  extends \Product\Model\CategoryBase {

    public function dataLabel()
    {
        return $this->name;
    }

}
