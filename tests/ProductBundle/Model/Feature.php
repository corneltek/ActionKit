<?php
namespace ProductBundle\Model;

class Feature 
extends \ProductBundle\Model\FeatureBase
{
    public function dataLabel() {
        return $this->name;
    }
}
