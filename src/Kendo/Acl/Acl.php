<?php
namespace Kendo\Acl;
use Kendo\Acl\RuleLoader;
use Kendo\Acl\MultiRoleInterface;
use Kendo\Acl\AccessObserver;
use Exception;
use InvalidArgumentException;

class Acl
{
    public $loader;
    public $observers = array();

    public function __construct(RuleLoader $loader)
    {
        $this->loader = $loader;
    }

    public function attach(AccessObserver $obs) {
        $this->observers["$obs"] = $obs;
    }

    public function detach(AccessObserver $obs) {
        delete($this->observers["$obs"]);
    }

    public function notifyAllow($role,$resource,$operation)
    {
        foreach( $this->observers as $observer ) {
            $observer->onAllow($this,$role,$resource,$operation);
        }
    }

    public function notifyDeny($role,$resource,$operation)
    {
        foreach( $this->observers as $observer ) {
            $observer->onDeny($this,$role,$resource,$operation);
        }
    }

    public function allow($role,$resource,$operation) {
        return true;
    }

    public function deny($role,$resource,$operation) {
        return false;
    }


    /**
     * Do authorize (notify access observer)
     */
    public function authorize($user,$resource,$operation) 
    {
        $allowed = $this->can($user,$resource,$operation);
        if($allowed) {
            $this->notifyAllow($role,$resource,$operation);
        } else {
            $this->notifyDeny($role,$resource,$operation);
        }
        return $allowed;
    }


    /**
     * Simply checks permisssion
     */
    public function can($user,$resource,$operation)
    {
        if( is_string($user) ) {
            $role = $user;
            if( true === $this->loader->authorize($role,$resource,$operation) ) 
                return true;
            return false;
        }
        elseif( $user instanceof MultiRoleInterface || method_exists($user,'getRoles') ) {
            foreach( $user->getRoles() as $role ) {
                if( true === $this->loader->authorize($role,$resource,$operation) )
                    return true;
            }
            return false;
        }
        throw new InvalidArgumentException;
    }

    public function cannot($user,$resource,$operation) {
        return ! $this->can($user,$resource,$operation);
    }

    static public function getInstance($loader = null)
    {
        static $instance;
        if( $instance )
            return $instance;
        return $instance = new self($loader);
    }
}


