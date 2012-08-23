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
            'resource' => $rule->resource['id'],
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
            'resource_id' => $ar->id,
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
        $resource->createOrUpdate( array(
            'name' => $res->name,
            'label' => $res->label,
        ),array('name'));

        if( ! $resource->id ) {
            throw new Exception("Can not write AccessResource to database");
        }
    }

    public function buildAndSync() {
        $this->build();
        $this->write();
    }

    public function write() {
        foreach( $this->resources as $res ) {
            $this->syncResource($res);
        }
        foreach( $this->rules as $rule ) {
            $this->syncRule($rule);
        }
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


