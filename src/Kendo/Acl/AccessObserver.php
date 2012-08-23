<?php
namespace Kendo\Acl;
use Kendo\Acl\Acl;

interface AccessObserver
{
    public function onAllow(Acl $o);
    public function onDeny(Acl $o);
}


