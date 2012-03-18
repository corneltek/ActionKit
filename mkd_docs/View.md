# Phifty View Class

## Basic View Class

Phifty\View is not inherited by Phifty\View\Engine, Phifty\View is a stand
alone class, and has an engine to render template.

To render template with template engin and args.

Example:

    $engine = Phifty\View\Engine::getEngine( 'smarty' );
    $engine = Phifty\View\Engine::getEngine( 'smarty' , $engineOptions );

    $view = new \Phifty\View( $engine , array( 
            "template_dirs" => array( ... ),
            "cache_dir" => ....,
            "scope" => 'core', 'app' , 'plugin/SB'
    ));

    $view = new \Phifty\View( $engine );
    $view->books = $data;
    $view->render( 'admin/login_page.tpl');

