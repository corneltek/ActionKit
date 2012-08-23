<?php
namespace Kendo\Acl;
use Kendo\Model\AccessResource as AR;
use Kendo\Model\AccessResourceCollection as ARCollection;
use Kendo\Model\AccessControl as AC;
use Kendo\Model\AccessControlCollection as ACCollection;
use Exception;

class DatabaseRule extends Rule

    public function ARRecordArguments()
    {
        $args = array( 
            'resource' => $this->resource['id'],
            'operation' => $this->operation['id'],
            'description' => $this->desc,
        );
        if( isset($this->resource['label'] )) )
            $args['resource_label'] = $this->resource['label'];
        if( isset($this->operation['label'] )) )
            $args['operation_label'] = $this->operation['label'];
        return $args;
    }

    /**
     * Sync Rule item to database.
     */
    public function sync() {
        // sync resource operation table
        $ar = new AR;
        $ret = $ar->createOrUpdate( $this->ARRecordArguments() ,array('resource','operation'));
        if( ! $ret->success )
            throw new $ret->exception;

        $ac = new AC;
        $ret = $ac->loadOrCreate(array( 
            'resource_id' => $ar->id,
            'role' => $this->role,
            'allow' => $this->allow,
        ));
        if( ! $ret->success )
            throw new $ret->exception;
    }
}

/**
 * Use access control rules from database.
 */
abstract class DatabaseRules extends BaseRules
{

    abstract function build();

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

    public function buildAndSync() {
        $this->build();
        foreach( $this->rules as $rule ) {
            $rule->sync();
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


