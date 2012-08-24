<?php
namespace Kendo\Model;

class AccessResource 
extends \Kendo\Model\AccessResourceBase
{

    public function getControlsByRole($role) {
        $controls = new AccessControlCollection;
        $controls->where()
            ->equal('role', $role);
        $controls->order('rule_id','desc');
        $controls->join(new AccessRule,'LEFT','r');
        return $controls;
    }
    
}
