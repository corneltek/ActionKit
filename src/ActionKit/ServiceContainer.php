<?php
namespace ActionKit;
use Pimple\Container;
use ActionKit\ActionGenerator;

/**
 *
 * Provided services:
 *
 *    generator:  ActionKit\ActionGenerator
 *    cache_dir
 *
 * Usage:
 *
 *    $container = new ServiceContainer;
 *    $generator = $container['generator'];
 *
 */
class ServiceContainer extends Container
{

    public function __construct()
    {
        $self = $this;

        $this['generator'] = function($c) use($self) {
            return new ActionGenerator(array( 'cache' => true ));
        };
    }
}