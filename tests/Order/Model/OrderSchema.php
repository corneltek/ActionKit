<?php
namespace Order\Model;

class OrderSchema extends \LazyRecord\Schema\SchemaDeclare
{
    public function schema()
    {
        $this->column('sum')
            ->integer();

        $this->column('qty')
            ->integer();
    }
}

