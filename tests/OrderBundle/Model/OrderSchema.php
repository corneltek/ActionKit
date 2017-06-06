<?php

namespace OrderBundle\Model;

use Magsql\Raw;

class OrderSchema extends \Maghead\Schema\DeclareSchema
{
    public function schema()
    {
        $this->column('sum')
            ->integer();

        $this->column('quantity')
            ->integer();

        $this->column('amount')
            ->notNull()
            ->integer();

        $this->column('updated_at')
            ->timestamp()
            ->notNull()
            ->isa('DateTime')
            ->renderAs('DateTimeInput')
            ->default(new Raw('CURRENT_TIMESTAMP'))
            ->onUpdate(new Raw('CURRENT_TIMESTAMP'))
            ->label(_('更新時間'))
            ;

        $this->column('created_at')
            ->timestamp()
            ->isa('DateTime')
            ->null()
            ->renderAs('DateTimeInput')
            ->label( _('建立時間') )
            ->default(function() {
                return new \DateTime;
            })
            ;

        $this->hasMany('items', OrderItemSchema::class, 'order_id', 'id');
    }
}
