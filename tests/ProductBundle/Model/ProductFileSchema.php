<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\SchemaDeclare;

class ProductFileSchema extends SchemaDeclare
{

    public function getLabel()
    {
        return '產品檔案';
    }

    public function schema()
    {

        $this->column( 'product_id' )
            ->integer()
            ->refer('ProductBundle\\Model\\Product')
            ->renderAs('SelectInput')
            ->label('產品');

        $this->column( 'title' )
            ->varchar(130)
            ->label('檔案標題');

            $this->column( 'vip' )
                ->boolean()
                ->renderAs('CheckboxInput')
                ->label('會員專用')
                ;
        }

        $this->column( 'mimetype' )
            ->varchar(16)
            ->label('檔案格式')
            ;

        $this->column( 'file' )
            ->varchar(130)
            ->required()
            ->label('檔案');

    }

}


