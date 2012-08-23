<?php
namespace Kendo\Acl;
use Exception;

class RuleOrderException extends Exception { }

class BaseRules
{
    public $allowRules = array();
    public $denyRules = array();
    public $order = array('allow','deny');

    public function __construct() { }

    public function add($roleId, $resourceId, $operationId, $allow )
    {
        if( $allow ) {
            $this->addAllow($roleId,$resourceId,$operationId);
        } else {
            $this->addDeny($roleId,$resourceId,$operationId);
        }
    }

    public function addAllow( $roleId, $resourceId, $operationId ) 
    {
        if( ! isset($this->allowRules[ $roleId ]) ) {
            $this->allowRules[ $roleId ] = array();
        }
        if( ! isset($this->allowRules[ $roleId ][ $resourceId ]) ) {
            $this->allowRules[ $roleId ][ $resourceId ] = array();
        }
        $this->allowRules[$roleId][$resourceId][ $operationId ] = $allow;
    }

    public function addDeny( $roleId, $resourceId, $operationId )
    {
        if( ! isset($this->denyRules[ $roleId ]) ) {
            $this->denyRules[ $roleId ] = array();
        }
        if( ! isset($this->denyRules[ $roleId ][ $resourceId ]) ) {
            $this->denyRules[ $roleId ][ $resourceId ] = array();
        }
        $this->denyRules[$roleId][$resourceId][ $operationId ] = $allow;
    }

    public function hasRule($rules,$roleId,$resourceId,$operationId) 
    {
        return isset( $rules[ $roleId ][ $resourceId ][ $operationId ] );
    }

    public function authorize($roleId, $resoureId, $operationId)
    {
        if( $this->order[0] == 'allow' ) {
            if( $this->hasRule( $this->allowRules, $roleId, $resourceId, $operationId ) )
                return true;
            return false;
        }
        elseif( $this->order[0] == 'deny' ) {
            if( $this->hasRule( $this->denyRules, $roleId, $resourceId, $operationId ) )
                return false;
            return true;
        }
        else {
            throw new RuleOrderException('authorize order is not defined.');
        }
    }

    public function export() {
        return array(
            'allow' => $this->allowRules,
            'deny'  => $this->denyRules,
        );
    }
}


