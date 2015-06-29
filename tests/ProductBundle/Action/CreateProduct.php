<?php
namespace ProductBundle\Action;
use ActionKit;
use ProductBundle\Model\ProductImage;
use ProductBundle\Model\Feature;
use ProductBundle\Model\Resource;
use ProductBundle\Model\FeatureRel;
use ActionKit\RecordAction\CreateRecordAction;

class CreateProduct extends CreateRecordAction
{
    public $recordClass = 'ProductBundle\\Model\\Product';

    public $mixin;

    public function preinit()
    {
        $this->mixin = new ProductBaseMixin($this);
        $this->mixin->preinit();
    }

    public function schema()
    {
        $this->mixin->schema();
    }

    public function successMessage($ret) 
    {
        return '產品資料 ' . $this->record->name . ' 建立成功';
    }
}

