<?php
namespace ProductBundle\Action;
use ActionKit\RecordAction\CreateRecordAction;

class CreateProductImage  extends CreateRecordAction
{
    public $recordClass = 'ProductBundle\Model\ProductImage';

    public function schema()
    {
        $this->useRecordSchema();

        $imageSizeLimit = 1024; // 1024kb
        $imageSize = [
            'width' => 512,
            'height' => 512,
        ];
        $autoResize = true;

        $this->param('image','Image')
            ->sizeLimit($imageSizeLimit)
            ->size( $imageSize )
            ->autoResize($autoResize)
            ->sourceField( 'large' )
            ->required()
            ->hint('product image hint')
            ->hintFromSizeInfo()
            ->label('product image label')
            ->putIn('tests/upload')
            ;

        $this->param('large','Image')
            ->sizeLimit($imageSizeLimit)
            ->size( $imageSize )
            ->autoResize($autoResize)
            ->hint('product large image hint')
            ->hintFromSizeInfo()
            ->label('product large image label')
            ->putIn('tests/upload')
            ;

    }


}




