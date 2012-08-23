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

        $this->column('rule_id')
            ->integer()
            ->required();

        $this->column('allow')
            ->boolean()
            ->default(false);

        $this->belongsTo('rule','Kendo\Model\AccessRuleSchema','id','rule_id');
    }
}

