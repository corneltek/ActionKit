<?php
namespace Kendo\Model;
use LazyRecord\Schema\SchemaDeclare;

class AccessControlSchema extends SchemaDeclare
{
    public function schema() 
    {
        // role identity
        $this->column('role')
            ->varchar(32);

        $this->column('resource_id')
            ->integer()
            ->required();

        $this->column('allow')
            ->boolean()
            ->default(false);

        $this->belongsTo('resource','Kendo\Model\AccessResourceSchema','id','resource_id');
    }
}

