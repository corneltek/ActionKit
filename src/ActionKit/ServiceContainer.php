<?php
namespace ActionKit;
use Pimple\Container;
use ActionKit\ActionGenerator;
use ActionKit\CsrfTokenProvider;
use Twig_Loader_Filesystem;
use ReflectionClass;

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

        $this['csrf'] = function() {
            return new CsrfTokenProvider;
        };

        $this['csrf_token'] = function() {
            // try to load csrf token in the current session
            $token = $this['csrf']->loadTokenWithSessionKey('_csrf_token', true);
            if ($token == null || !$token->checkExpiry($_SERVER['REQUEST_TIME'])) {
                $token = $this['csrf']->generateToken();
            }
            return $token->hash;
        };


        // The default twig loader
        $this['twig_loader'] = function($c) {
            $refClass = new ReflectionClass('ActionKit\\ActionGenerator');
            $templateDirectory = dirname($refClass->getFilename()) . DIRECTORY_SEPARATOR . 'Templates';

            // add ActionKit built-in template path
            $loader = new Twig_Loader_Filesystem([]);
            $loader->addPath($templateDirectory, 'ActionKit');
            return $loader;
        };

        $this['generator'] = function($c) use($self) {
            return new ActionGenerator(array( 'cache' => true ));
        };
    }
}
