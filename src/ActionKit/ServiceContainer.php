<?php
namespace ActionKit;
use Pimple\Container;
use ActionKit\ActionGenerator;

/**
 *
 * Provided services:
 *
 *    actionGenerator:  ActionKit\ActionGenerator
 *
 * Usage:
 *
 *    $container = ServiceContainer::getInstance();
 *    $generator = $container['actionGenerator'];
 *
 */
class ServiceContainer extends Container
{

    public function __construct()
    {
        $this['actionGenerator'] = function($c) {
            return new ActionGenerator;
        };
    }

    public static function getInstance()
    {
        static $self;
        if ( $self ) {
            return $self;
        }

        return $self = new static;
    }
}