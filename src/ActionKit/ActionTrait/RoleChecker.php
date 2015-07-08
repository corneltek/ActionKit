<?php
namespace ActionKit\ActionTrait;
use Kendo\Acl\MultiRoleInterface;
use Exception;

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
        } else {
            throw new Exception("Unsupported current user object");
        }
        return $this->deny();
    }

    public function allow($message = null)
    {
        return array(true, $message ?: $this->permissionAllowedMessage());
    }

    public function deny($message = null)
    {
        return array(false, $message ?: $this->permissionDeniedMessage());
    }

    public function getAllowedRoles() 
    {
        return $this->allowedRoles;
    }

    public function permissionDeniedMessage()
    {
        return 'Permission denied.';
    }

    public function permissionAllowedMessage() 
    {
        return 'Permission allowed.';
    }
}
