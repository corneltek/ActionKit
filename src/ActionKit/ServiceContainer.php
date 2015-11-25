<?php
namespace ActionKit;
use Pimple\Container;
use ActionKit\ActionGenerator;
use ActionKit\CsrfTokenProvider;
use ActionKit\MessagePool;
use Twig_Loader_Filesystem;
use ReflectionClass;

/**
 * Provided services:
 *
 *    generator:  ActionKit\ActionGenerator
 *    cache_dir string
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
        parent::__construct();
        $this->preset();
    }

    protected function preset()
    {
        $self = $this;

        $this['locale'] = '';

        $this['messages'] = function($c) {
            if (isset($c['locale'])) {
                return new MessagePool($c['locale']);
            }
            return new MessagePool('en');
        };

        $this['csrf'] = function($c) {
            return new CsrfTokenProvider;
        };

        $this['csrf_token'] = $this->factory(function($c) {
            // try to load csrf token in the current session
            $token = $c['csrf']->loadToken(true);
            if ($token == null || !$token->checkExpiry($_SERVER['REQUEST_TIME'])) {
                $token = $c['csrf']->generateToken();
            }
            return $token->hash;
        });


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
