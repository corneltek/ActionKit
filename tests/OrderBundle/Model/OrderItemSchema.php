<?php
namespace OrderBundle\Model;

class OrderItemSchema extends \Maghead\Schema\DeclareSchema
{
    public function schema()
    {
        $this->column('quantity')
            ->integer()
            ->required()
            ;

        $this->column('subtotal')->integer();
    }
}

