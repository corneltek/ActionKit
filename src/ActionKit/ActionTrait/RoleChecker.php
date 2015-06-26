<?php
namespace ActionKit\ActionTrait;
use Kendo\Acl\MultiRoleInterface;

trait RoleChecker 
{
    public function currentUserCan($user, $right, $args = array())
    {
        if (!isset($user)) {
            return $this->deny();
        }

        if (is_string($user)) {
            if (in_array($user, $this->allowedRoles)) {
                return $this->allow();
            } else {
                return $this->deny();
            }
        } else if ($user instanceof MultiRoleInterface  || method_exists($user,'getRoles')) {
            foreach ($user->getRoles() as $role ) {
                if (in_array($role, $this->allowedRoles) ) {
                    return $this->allow();
                }
            }
            return $this->deny();
        }

        return $this->deny('Permission Denied.');
    }

    public function allow($message = null)
    {
        return array(true, $message);
    }

    public function deny()
    {
        return array(false, $this->permissionAllowedMessage());
    }

    public function getAllowedRoles() 
    {
        return $this->allowedRoles;
    }

    public function permissionAllowedMessage() {
        return 'Permission Denied.';
    }
}