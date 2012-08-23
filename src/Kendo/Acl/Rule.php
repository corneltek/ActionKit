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
        $this->operation = array( 'id' => $operation );
        $this->resource = array( 'id' => $resource );
        $this->allow = $allow;
    }

    public function operationLabel($label) {
        $this->operation['label'] = $label;
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
