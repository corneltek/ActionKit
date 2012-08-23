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

    public function notifyAllow() 
    {
        foreach( $this->observers as $observer ) {
            $observer->onAllow($this);
        }
    }

    public function notifyDeny()
    {
        foreach( $this->observers as $observer ) {
            $observer->onDeny($this);
        }
    }

    public function allow() {
        $this->notifyAllow();
        return true;
    }

    public function deny()
    {
        $this->notifyDeny();
        return false;
    }

    public function can($user,$resource,$operation)
    {
        if( is_string($user) ) {
            $role = $user;
            if( true === $this->loader->authorize($role,$resource,$operation) ) 
                return $this->allow();
            return $this->deny();
        }
        elseif( $user instanceof MultiRoleInterface || method_exists($user,'getRoles') ) {
            foreach( $user->getRoles() as $role ) {
                if( true === $this->loader->authorize($role,$resource,$operation) ) {
                    return $this->allow();
                }
            }
            return $this->deny();
        } else {
            throw new InvalidArgumentException;
        }
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


