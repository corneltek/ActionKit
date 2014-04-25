<?php
namespace ProductBundle\Model;

class Category 
extends \ProductBundle\Model\CategoryBase
{

    public $dataLabelField = 'name';

    public function dataLabel()
    {
        if ( $this->parent_id )
            return $this->parent->dataLabel() . ' &gt; ' . $this->name;
        return $this->name;
    }

    public function getParent()
    {
        if( $this->parent_id )
            return $this->parent;
    }

    public function getChilds()
    {
        $childs = new CategoryCollection;
        $childs->where(array( 'parent_id' => $this->id ));
        return $childs;
    }
}
