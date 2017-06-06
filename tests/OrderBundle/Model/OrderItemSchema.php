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

        $this->column('order_id')
            ->integer()
            ->required()
            ->unsigned()
            ->refer(OrderSchema::class);

        $this->column('subtotal')->integer();
    }
}

