<?php
namespace Kendo\Model;
use LazyRecord\Schema\SchemaDeclare;

class AccessResourceSchema extends SchemaDeclare
{
    function schema() {

        $this->column('resource')
            ->varchar(64)
            ->required();

        $this->column('resource_label')
            ->varchar(128);

        $this->column('operation')
            ->varchar(64)
            ->required();

        $this->column('operation_label')
            ->varchar(128);

        $this->column('description')
            ->text();
    }
}


