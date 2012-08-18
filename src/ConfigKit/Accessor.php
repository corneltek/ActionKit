<?php
namespace ConfigKit;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Accessor
    implements ArrayAccess, IteratorAggregate
{

    public $config = array();

    function __construct($config = array() )
    {
        $this->config = $config;
    }

    public function getIterator() 
    {
        return new ArrayIterator($this->config ?: array() );
    }
    
    public function offsetSet($name,$value)
    {
        $this->config[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($this->config[ $name ]);
    }
    
    public function offsetGet($name)
    {
        if( isset($this->config[$name]) ) {
            if( is_array($this->config[$name]) ) {
                return new Accessor($this->config[$name]);
            }
            return $this->config[ $name ];
        }
    }
    
    public function offsetUnset($name)
    {
        unset($this->config[$name]);
    }
    
    public function toArray()
    {
        return $this->config;
    }

    public function isEmpty()
    {
        return null === $this->config || empty( $this->config );
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }
    
}

