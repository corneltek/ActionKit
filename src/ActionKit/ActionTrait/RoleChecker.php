<?php
namespace ActionKit\ActionTrait;
trait RoleChecker 
{
    public function currentUserCan($user) 
    {
        if (is_string($user)) {
            if (in_array($user, $this->allowedRoles)) {
                return $this->allow('');
            } else {
                $this->deny('Permission Denied.');
            }
        } else if ($user instanceof Kendo\Acl\MultiRoleInterface  || method_exists($user,'getRoles')) {
            foreach ($user->getRoles() as $role ) {
                if (in_array($role, $this->allowedRoles) ) {
                    return $this->allow('');
                }
            }
            return $this->deny('Permission Denied.');
        }

        return $this->deny('Permission Denied.');
    }

    public function allow($message)
    {
        return array('accept' => true, 'message' => $message);
    }

    public function deny($message)
    {
        return array('accept' => false, 'message' => $message);
    }

    public function getAllowedRoles() 
    {
        return $this->allowedRoles;
    }
}