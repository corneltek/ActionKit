<?php
namespace Kendo\Acl;

class BaseRules
{
    public $allowRules = array();
    public $denyRules = array();

    public function add($roleId, $resourceId, $operationId, $allow )
    {

    }

    public function addAllowRule( $roleId, $resourceId, $operationId ) 
    {
        if( ! isset($this->allowRules[ $roleId ]) ) {
            $this->allowRules[ $roleId ] = array();
        }
        if( ! isset($this->allowRules[ $roleId ][ $resourceId ]) ) {
            $this->allowRules[ $roleId ][ $resourceId ] = array();
        }
        $this->allowRules[$roleId][$resourceId][ $operationId ] = $allow;
    }

    public function addDenyRule( $roleId, $resourceId, $operationId )
    {
        if( ! isset($this->denyRules[ $roleId ]) ) {
            $this->denyRules[ $roleId ] = array();
        }
        if( ! isset($this->denyRules[ $roleId ][ $resourceId ]) ) {
            $this->denyRules[ $roleId ][ $resourceId ] = array();
        }
        $this->denyRules[$roleId][$resourceId][ $operationId ] = $allow;
    }

    public function export() {
        return array(
            'allow' => $this->allowRules,
            'deny'  => $this->denyRules,
        );
    }

}


