<?php
namespace Kendo\Acl;

class Rule 
{
    public $role;
    public $resource;
    public $operation;

    /**
     * Description
     */
    public $desc;
    public $allow = false;

    public function __construct($role,$resource,$operation,$allow) {
        $this->role = $role;
        $this->resource = $resource;
        $this->operation = array( 'id' => $operation );
        $this->allow = $allow;
    }

    public function label($label) {
        $this->operation['label'] = $label;
        return $this;
    }

    public function allow($allow)
    {
        $this->allow = $allow;
        return $this;
    }

    public function desc($desc) {
        $this->desc = $desc;
        return $this;
    }

    public function toArray() {
        return array(
            'role' => $this->role,
            'operation' => $this->operation,
            'resource' => $this->resource,
            'allow' => $this->allow,
            'desc' => $this->desc,
        );
    }
}
