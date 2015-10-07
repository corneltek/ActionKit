<?php
namespace User\Model;

class UserSchema extends \LazyRecord\Schema\DeclareSchema
{
    public function schema()
    {
        $this->column('name')
            ->varchar(30);

        $this->column('email')
            ->varchar(128);

        $this->column('password')
            ->varchar(128);
    }
}

