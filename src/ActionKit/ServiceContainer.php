<?php
namespace ActionKit;
use Pimple\Container;
use ActionKit\ActionGenerator;

/**
 *
 * Provided services:
 *
 *    actionGenerator:  ActionKit\ActionGenerator
 *    cache_dir
 *
 * Usage:
 *
 *    $container = new ServiceContainer;
 *    $generator = $container['actionGenerator'];
 *
 */
class ServiceContainer extends Container
{

    public function __construct()
    {
        $self = $this;

        $this['cache_dir'] = __DIR__ . DIRECTORY_SEPARATOR . 'Cache';

        $this['actionGenerator'] = function($c) use($self) {
            return new ActionGenerator(array( 'cache' => true , 'cache_dir' => $self['cache_dir'] ));
        };
    }
}