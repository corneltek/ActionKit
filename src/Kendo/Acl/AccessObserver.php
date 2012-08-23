<?php
namespace Kendo\Acl;
use Kendo\Acl\Acl;

interface AccessObserver
{
    public function success(Acl $o);
    public function fail(Acl $o);
}


