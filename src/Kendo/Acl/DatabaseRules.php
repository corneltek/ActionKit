<?php
namespace Kendo\Acl;
use Kendo\Model\AccessResource as AR;
use Kendo\Model\AccessResourceCollection as ARCollection;
use Kendo\Model\AccessControl as AC;
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

    public function getARRecordArguments($rule)
    {
        $args = array( 
            'resource' => $rule->resource['id'],
            'operation' => $rule->operation['id'],
            'description' => $rule->desc,
        );
        if( isset($rule->resource['label'] ) )
            $args['resource_label'] = $rule->resource['label'];
        if( isset($rule->operation['label'] ) )
            $args['operation_label'] = $rule->operation['label'];
        return $args;
    }


    /**
     * Sync Rule item to database.
     */
    public function syncRule($rule) {
        // sync resource operation table
        $ar = new AR;
        $ret = $ar->createOrUpdate( $this->getARRecordArguments($rule) ,array('resource','operation'));
        if( ! $ret->success )
            throw new $ret->exception;

        $ac = new AC;
        $ret = $ac->loadOrCreate(array( 
            'resource_id' => $ar->id,
            'role' => $rule->role,
            'allow' => $rule->allow,
        ));
        if( ! $ret->success )
            throw new $ret->exception;
    }

    public function buildAndSync() {
        $this->build();
        foreach( $this->rules as $rule ) {
            $this->syncRule($rule);
        }
    }

    /*
        function build() {
            $this->add('admin','users','create',true)
                ->resourceLabel( 'Users' );
                ->operationLabel( 'Create User' );
        }
    */

}


