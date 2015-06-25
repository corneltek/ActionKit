<?php
namespace ActionKit\ActionTrait;
trait RoleChecker 
{
    public function currentUserCan($user) 
    {
        if (is_string($user)) {
            return in_array($user, $this->allowedRoles);
        } else if ($user instanceof Kendo\Acl\MultiRoleInterface  || method_exists($user,'getRoles')) {
            foreach ($user->getRoles() as $role ) {
                if (in_array($role, $this->allowedRoles) )
                    return true;
            }
            return false;
        }
        
    }
    public function getAllowedRoles() 
    {
        return $this->allowedRoles;
    }
}