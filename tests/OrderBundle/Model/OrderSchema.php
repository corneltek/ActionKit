<?php
namespace OrderBundle\Model;

class OrderSchema extends \LazyRecord\Schema\DeclareSchema
{
    public function schema()
    {
        $this->column('sum')
            ->integer();

        $this->column('qty')
            ->integer();
    }
}

