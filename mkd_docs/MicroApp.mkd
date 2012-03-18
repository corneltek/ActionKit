
MicroApp
========

A simpler way to define routing 

	function init()
	{
		$this->route( '/page'  , 'Page' );  // route to \Core\Controller\Page
	}

Or specify the engine arguments

	$this->route( '/page' , array( "engine" => "php", "scope" => "core" , "template" => "page.html" ));
	$this->route( '/page' , array( "engine" => "twig", "template" => "page.html" ));
	$this->route( '/page' , array( "engine" => "twig", "template" => "page.html" ));

default scope is "core" (in Core), "app" (in App)

	self->route
		ControllerDispatcher->add ...
		ControllerDispatcher->match ...

About template scope:

	scope is optional.

	for App class, we should use app/template, Core/template as default.
	for Core class, we should use app/template, Core/template as default.
	for Plugin classes, we should use plugin/template as default.

XXX: Problem: sometimes we need to override app,core template from plugin. (yes?no?)



RouterSet
---------

RouterSet, route to class methods (set)

In your MicroApp class, like App or Core or Plugin..:

    class Core {
        $this->route( '/admin' , array( 'routerset' => 'SampleRouterSet' ) );
        $this->expandRoute( '/admin' , 'RouterSet' );
    }


    class SampleRouterSet extends RouterSet
    {
        function table()
        {
        }

        function foo_bar()
        {
            $this->render( ... );
        }


        function test()
        {

        }

    }


Js and Css
----------

include your js and css files:


    function js()
    {
        return array(
            'js/*.js',
            'json/*.js'
        );
    }

    function css()
    {
        return array();
    }

