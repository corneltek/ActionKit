<?php
namespace ProductBundle\Action;
use ActionKit\RecordAction\CreateRecordAction;

class CreateProductFile  extends CreateRecordAction
{
    public $recordClass = 'ProductBundle\Model\ProductFile';

    public function schema()
    {
        $this->useRecordSchema();

        $sizeLimit = 1024; // 1024kb

        $this->replaceParam('file','File')
            ->sizeLimit($sizeLimit)
            ->required()
            ->hint('product file hint')
            ->label('product file label')
            ->putIn('tests/upload')
            ;

    }


}




