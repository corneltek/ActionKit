<?php
namespace Kendo\Acl;
use Kendo\Model\AccessResource as AR;
use Kendo\Model\AccessResourceCollection as ARCollection;
use Kendo\Model\AccessControl as AC;
use Kendo\Model\AccessControlCollection as ACCollection;
use Exception;

class Resource {

    public $id;
    public $label;

    public function __construct($id) {
        $this->id = $id;
    }

    public function __toString() {
        return $this->id;
    }

    public function toArray() {
        return array(
            'id' => $this->id,
            'label' => $this->label,
        );
    }
}

class Operation { 

    public $id;
    public $label;

    public function __construct($id) {
        $this->id = $id;
    }

    public function __toString() {
        return $this->id;
    }

    public function toArray() {
        return array(
            'id' => $this->id,
            'label' => $this->label,
        );
    }
}

class Rule {
    public $role;
    public $resource;
    public $operation;

    /**
     * description
     */
    public $desc;
    public $allow = false;

    public function __construct($role,$resource,$operation,$allow) {
        $this->role = $role;
        $this->operation = new Operation($operation);
        $this->resource = new Resource($operation);
        $this->allow = $allow;
    }


    /**
     * Sync Rule item to database.
     */
    public function sync() {
        // sync resource operation table
        $ar = new AR;
        $ret = $ar->createOrUpdate(array( 
            'resource' => $this->resource->id,
            'resource_label' => $this->resource->label,
            'operation' => $this->operation->id,
            'operation_label' => $this->operation->label,
            'description' => $this->desc,
        ),array('resource','operation'));

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

    public function toArray() {
        return array(
            'role' => $this->role,
            'operation' => $this->operation->toArray(),
            'resource' => $this->resource->toArray(),
            'allow' => $this->allow,
            'desc' => $this->desc,
        );
    }
}

/**
 * Use access control rules from database.
 */
abstract class DatabaseRules extends BaseRules
{


}


