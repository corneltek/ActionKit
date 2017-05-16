<?php
namespace OrderBundle\Model;

class OrderSchema extends \Maghead\Schema\DeclareSchema
{
    public function schema()
    {
        $this->column('sum')
            ->integer();

        $this->column('qty')
            ->integer();
    }
}

