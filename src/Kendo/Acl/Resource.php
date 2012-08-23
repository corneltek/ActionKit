<?php
namespace Kendo\Acl;

class Resource
{
    public $name;
    public $label;

    function __construct($name,$label = null)
    {
        $this->name = $name;
        if( $label )
            $this->label = $label;
    }

    public function name($name) {
        $this->name = $name;
    }

    public function label($label) {
        $this->label = $label;
    }

    public function toArray()
    {
        return array('label' => $this->label, 'name' => $this->name);
    }
}


