
Controller
==========


Mount A Controller
------------------

in your core/core.php, app/app.php:

declare

    function init()
    {
        $this->route( '/path' , '\App\Controller\Test' );

        $this->route( '/path' , array( 'template' => 'page.html' ) );
        $this->route( '/path' , array( 'controller' => 'Test' ) );
        $this->route( '/path' , array( 'routerset' => 'TestSet' ) );
        $this->page( '/path' , 'template.html');
    }

	class YourController extends Controller
	{
		
		function post()
		{
			$httpAgent = $this->env->server->HTTP_AGENT;
			$firstName = $this->env->post->first_name;
			$lastName = $this->env->post->last_name;
			$lastName = $this->env->get->last_name;
			$id = $this->env->get->id;
		}
	
		function run()
		{
            $var1 = $this->env->request->var1;
            $var2 = $this->env->session->var2;
            $var3 = $this->env->post->var3;
            $var4 = $this->env->get->var4;

            if( $this->env->post->has('login') ) {
                // ....

            }

			$this->render( 'template.html' , array( .... ) );
		}
	
	}

Create A Controller
-------------------

Run command to generate controller:

    ./phifty gen controller Test

Simplest Controller:

    class Test extends \Phifty\Controller
    {
        function run()
        {
            $this->render( 'template.html' , array( 'users' => array( 'c9s' ) ));
        }

    }

Will Get method, Post method:

    class Test extends \Phifty\Controller
    {

        /* optional: post method */
        function post()
        {

        }

        /* optional: get method */
        function get()
        {

        }

        function run()
        {
            $this->render( 'template.html' , array( 'users' => array( 'c9s' ) ));
        }

    }


Render method call graph
------------------------
    controller::render()
    => view::render()
    => engine::render()


Dispatching call graph
----------------------

    ControllerDispatcher->dispatch
        -> PathDispatch <- Dispatcher
    Router->run

Path Dispatcher
---------------

    $pathd = new PathDispatcher;
    $pathd->add( $path , array( .... ) , array( .... match ...  ) );

Dispatcher
----------
Now the routerset needs a path dispatcher, which is pretty easy

which can handle array. return result array.

    $d = new Dispatcher();
    $d->insert( .... );
    $d->add( array( 'path' => $path ,'regexp' => 1 , 'data' => $args ));
    $d->add( array( 'path' => $path ,'data'   => $args ));
    $dispatch = $d->dispatch( '/path/to/some/where' );

    echo $dispatch['path']
    echo $dispatch['data']

### Rule Options

* regexp
* match
* matchPrefix

