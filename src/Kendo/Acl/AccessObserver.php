<?php
namespace Kendo\Acl;
use Kendo\Acl\Acl;

interface AccessObserver
{
    public function onAllow($o,$role,$resource,$operation);
    public function onDeny($o ,$role,$resource,$operation);
}


