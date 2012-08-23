<?php
namespace Kendo\Acl;
use Kendo\Acl\RuleLoader;
use Kendo\Acl\MultiRoleInterface;
use Exception;
use InvalidArgumentException;

class Acl
{
    public $loader;

    public function __construct(RuleLoader $loader)
    {
        $this->loader = $loader;
    }

    public function can($user,$resource,$operation)
    {
        if( is_string($user) ) {
            $role = $user;
            return $this->loader->authorize($role,$resource,$operation);
        }
        elseif( $user instanceof MultiRoleInterface ) {
            foreach( $user->getRoles() as $role ) {
                if( true === $this->loader->authorize($role,$resource,$operation) )
                    return true;
            }
            return false;
        } else {
            throw new InvalidArgumentException;
        }
    }

    public function cannot($user,$resource,$operation) {
        return ! $this->can($user,$resource,$operation);
    }

    static public function getInstance($loader = null)
    {
        return new self($loader);
    }
}


