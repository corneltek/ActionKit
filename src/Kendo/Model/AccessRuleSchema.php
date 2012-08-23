<?php
namespace Kendo\Model;
use LazyRecord\Schema\SchemaDeclare;

class AccessRuleSchema extends SchemaDeclare
{
    function schema() {

        $this->column('resource')
            ->varchar(64)
            ->required();

        $this->column('operation')
            ->varchar(64)
            ->required();

        $this->column('operation_label')
            ->varchar(128);

        $this->column('description')
            ->text();
    }
}


