<?php
namespace Kendo\Acl;

class RuleLoader
{
    public $rules = array();

    public $fallbackAllow = false;

    public function load($rule) {
        if( is_string($rule) && class_exists($rule,true) ) {
            $this->rules[] = new $rule;
        } else {
            $this->rules[] = $rule;
        }
    }

    public function authorize($role,$resource,$operation)
    {
        foreach( $this->rules as $rule ) {
            $result = $rule->authorize($role,$resource,$operation);
            if( $result === true ) {
                return true;
            } elseif( $result === false ) {
                return false;
            } elseif( $result === null ) {
                // continue
            }
        }
        return $this->fallbackAllow;
    }
}


