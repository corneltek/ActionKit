<?php
namespace Kendo\Acl;
use Kendo\Model\AccessResource;
use Kendo\Model\AccessResourceCollection as ARCollection;
use Kendo\Model\AccessRule;
use Kendo\Model\AccessRuleCollection;
use Kendo\Model\AccessControl;
use Kendo\Model\AccessControlCollection as ACCollection;
use Exception;

/**
 * Use access control rules from database.
 */
abstract class DatabaseRules extends BaseRules
{

    public function __construct() {
        $this->cacheSupport = extension_loaded('apc');
        if( $this->cacheSupport ) {
            $key = get_class($this);
            if( $cache = apc_fetch($key) ) {
                $this->import($cache);
                return;
            } else {
                $this->buildAndSync();
                apc_store($key,$this->export(), $this->cacheExpiry);
            }
        } else {
            $this->buildAndSync();
        }
    }

    public function getRuleRecordArguments($rule)
    {
        $args = array( 
            'resource' => $rule->resource,
            'operation' => $rule->operation['id'],
            'description' => $rule->desc,
        );
        if( isset($rule->operation['label'] ) )
            $args['operation_label'] = $rule->operation['label'];
        return $args;
    }


    /**
     * Sync Rule item to database.
     */
    public function syncRule($rule) {
        // sync resource operation table
        $ar = new AccessRule;
        $ret = $ar->createOrUpdate( $this->getRuleRecordArguments($rule) ,array('resource','operation'));
        if( ! $ret->success )
            throw new $ret->exception;

        $ac = new AccessControl;
        $ret = $ac->loadOrCreate(array( 
            'rule_id' => $ar->id,
            'role' => $rule->role,
            'allow' => $rule->allow,
        ));
        if( ! $ret->success )
            throw new $ret->exception;

        // override default allow values
        $rule->allow = $ac->allow;
    }

    public function syncResource($res)
    {
        $resource = new AccessResource;
        $ret = $resource->createOrUpdate( array(
            'name' => $res->name,
            'label' => $res->label,
        ),array('name'));
        if( ! $ret->success )
            throw $ret->exception;
    }

    public function buildAndSync() {
        $this->build();
        $this->write();
    }


    /**
     * Load rules from database.
     */
    public function load() 
    {

    }

    public function write() {
        foreach( $this->resources as $res ) {
            $this->syncResource($res);
        }
        foreach( $this->rules as $rule ) {
            $this->syncRule($rule);
        }
    }

    public function addAllowRule($roleId, $resourceId, $operationId) {
        /*
        $ar = new AccessRule;
        $ret = $ar->load(array('resource' => $resourceId,'operation' => $operationId ));
        $ac = new AccessControl;
        $ac->load(array('role' => $roleId , 'rule_id' => $ar));
        */
        return parent::addAllowRule( $roleId, $resourceId, $operationId );
    }

    public function addDenyRule( $roleId, $resourceId, $operationId) {
        return parent::addDenyRule( $roleId, $resourceId, $operationId );
    }
    
    

    /*
    function build() {
        $this->resource( 'users' )
            ->label('User Management');

        $this->add('admin','users','create',true)
            ->operationLabel( 'Create User' );
    }
    */

}


