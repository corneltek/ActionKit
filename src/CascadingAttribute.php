<?php

class CascadingAttribute
{
    const TypeScalar = 1;
    const TypeArray = 2;
    const TypeCallback = 3;

    protected $attrs;

    function __call( $m , $args ) 
    {
        if( isset($this->attrs[ $m ] ) ) {
            $t = $this->attrs[ $m ];
            switch( $t )
            {
            case self::TypeScalar:
                $this->$m = $args[0];
                break;
            case self::TypeArray:
                $this->$m = $args;
                break;
            case self::TypeCallback:

                if( ! is_callable($args[0]) )
                    throw new \ErrorException("$m is not callable.");

                $this->$m = $args[0];
                break;
            default:
                throw new \ErrorException('Unknown attribute type:' . $t );
            }
        }
        else {
			if( property_exists( $this , $m ) ) {

#  				if( ! isset($args[0] ))
#  					throw new \ErrorException("Cascading property '$m' argument is not defined." . var_export( $args , true ) );

				$this->$m = $args[0];
			} else {
                throw new \ErrorException("Undefined attribute $m");
			}
        }
        return $this;
    }
}


