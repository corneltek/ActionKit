<?php
namespace Kendo\Acl;
use Exception;

class RuleOrderException extends Exception { }

abstract class BaseRules
{
    public $allowRules = array();

    public $denyRules = array();

    public $order = array('allow','deny');

    public $cacheSupport = false;

    public $cacheExpiry = 1200;

    public $ruleClass = 'Kendo\Acl\Rule';

    /**
     * Contains rule objects after build,
     * when cache exists, this array is empty.
     */
    public $rules = array();

    public $resources = array();

    public $cacheLoaded = false;

    public $cacheEnable = true;


    public function __construct() {
        $this->cacheSupport = extension_loaded('apc');
        if( $this->cacheEnable && $this->cacheSupport ) {
            $key = get_class($this);
            if( $cache = apc_fetch($key) ) {
                $this->import($cache);
                $this->cacheLoaded = true;
                return;
            } else {
                $this->build();
                apc_store($key,$this->export(), $this->cacheExpiry);
            }
        } else {
            $this->build();
        }
    }

    abstract function build();

    public function resource($resource)
    {
        return $this->resources[ $resource ] = new Resource($resource);
    }

    public function hasResource($resource) 
    {
        return isset($this->resources[ $resource ]);
    }

    public function getResource($resource)
    {
        if( isset($this->resources[ $resource ]) ) {
            return $this->resources[ $resource ];
        }
    }

    public function rule($roleId, $resourceId, $operationId, $allow )
    {
        if( $allow ) {
            return $this->addAllowRule($roleId,$resourceId,$operationId);
        } else {
            return $this->addDenyRule($roleId,$resourceId,$operationId);
        }
    }

    public function addRule($rule)
    {
        if( $rule->allow ) {
            $this->addAllowRule($rule->role,$rule->resource,$rule->operation['id']);
        } else {
            $this->addDenyRule($rule->role,$rule->resource,$rule->operation['id']);
        }
    }

    public function addAllowRule($roleId, $resourceId, $operationId)
    {
        $rule = new $this->ruleClass($roleId,$resourceId,$operationId,true);
        if( ! isset($this->allowRules[ $roleId ]) ) {
            $this->allowRules[ $roleId ] = array();
        }
        if( ! isset($this->allowRules[ $roleId ][ $resourceId ]) ) {
            $this->allowRules[ $roleId ][ $resourceId ] = array();
        }
        // not to override
        if( ! isset($this->allowRules[$roleId][$resourceId][$operationId]) ) {
            if( ! in_array($rule,$this->rules,true) )
                $this->rules[] = $rule;
            return $this->allowRules[$roleId][$resourceId][ $operationId ] = $rule;
        }
    }

    public function addDenyRule( $roleId, $resourceId, $operationId)
    {
        $rule = new $this->ruleClass($roleId,$resourceId,$operationId,false);
        if( ! isset($this->denyRules[ $roleId ]) ) {
            $this->denyRules[ $roleId ] = array();
        }
        if( ! isset($this->denyRules[ $roleId ][ $resourceId ]) ) {
            $this->denyRules[ $roleId ][ $resourceId ] = array();
        }
        if( ! in_array($rule,$this->rules,true) )
            $this->rules[] = $rule;
        return $this->denyRules[ $roleId ][$resourceId][ $operationId ] = $rule;
    }

    public function hasRule($rules,$roleId,$resourceId,$operationId) 
    {
        return isset( $rules[ $roleId ][ $resourceId ][ $operationId ] );
    }

    public function getRule($rules,$roleId,$resourceId,$operationId)
    {
        if( isset( $rules[ $roleId ][ $resourceId ][ $operationId ] ) )
            return $rules[ $roleId ][ $resourceId ][ $operationId ];
    }

    public function removeRule($rules,$roleId,$resourceId,$operationId) 
    {
        $rule = $this->getRule($rules,$roleId,$resourceId,$operationId);
        if( isset( $rules[ $roleId ][ $resourceId ][ $operationId ] ) ) {
            unset( $rules[ $roleId ][ $resourceId ][ $operationId ] );
        }
        return $rule;
    }

    public function removeAllowRule($roleId,$resourceId,$operationId)
    {
        return $this->removeRule($this->allowRules,$roleId,$resourceId,$operationId);
    }

    public function removeDenyRule($roleId,$resourceId,$operationId)
    {
        return $this->removeRule($this->denyRules,$roleId,$resourceId,$operationId);
    }


    public function getAllowRule($roleId,$resourceId,$operationId) 
    {
        return $this->getRule( $this->allowRules , $roleId, $resourceId, $operationId);
    }

    public function getDenyRule($roleId,$resourceId,$operationId)
    {
        return $this->getRule( $this->denyRules, $roleId, $resourceId, $operationId);
    }

    public function authorize($roleId, $resourceId, $operationId)
    {
        if( $this->order[0] == 'allow' ) {
            if( $this->hasRule( $this->allowRules, $roleId, $resourceId, $operationId ) )
                return true;
            if( $this->hasRule( $this->denyRules, $roleId, $resourceId, $operationId ) )
                return false;
            return;
        }
        elseif( $this->order[0] == 'deny' ) {
            if( $this->hasRule( $this->denyRules, $roleId, $resourceId, $operationId ) )
                return false;
            if( $this->hasRule( $this->allowRules, $roleId, $resourceId, $operationId ) )
                return true;
            return;
        }
        else {
            throw new RuleOrderException('authorize order is not defined.');
        }
    }




    public function import($stash)
    {
        $this->allowRules = $stash['allow'];
        $this->denyRules = $stash['deny'];
        $this->order = $stash['order'];
        $this->resources = $stash['resources'];
    }

    public function export()
    {
        return array(
            'allow' => $this->allowRules,
            'deny'  => $this->denyRules,
            'order' => $this->order,
            'resources' => $this->resources,
        );
    }
}


