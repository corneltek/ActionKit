<?php
namespace ActionKit;
use \ArrayAccess;

/**
 *
 * Provided services:
 *
 *    actionRunner:  ActionKit\ActionRunner
 *    actionGenerator:  ActionKit\ActionGenerator
 *
 * Usage:
 *
 *    $container = ServiceContainer::getInstance();
 *    $actionRunner = $container['actionRunner'];
 *
 */
class ServiceContainer implements ArrayAccess
{
    private $container = array();

    public function __construct()
    {
        $this->container['actionRunner'] = ActionRunner::getInstance();
        $this->container['actionGenerator'] = new ActionGenerator;
    }

    public static function getInstance()
    {
        static $self;
        if ( $self ) {
            return $self;
        }

        return $self = new static;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}