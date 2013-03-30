<?php
namespace Product\Action;
use ActionKit;
use Phifty\FileUtils;
use Product\Model\ProductImage;
use Product\Model\Feature;
use Product\Model\Resource;
use Product\Model\FeatureRel;
use ActionKit\RecordAction\CreateRecordAction;

class CreateProduct extends CreateRecordAction
{
    public $recordClass = 'Product\\Model\\Product';

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

