<?php
require 'bootstrap.php';

$config = new LazyRecord\ConfigLoader;
$config->load('../.lazy.yml');
$config->init();

ActionKit\CRUD::generate('Product\\Model\\ProductCategory', 'Create');
ActionKit\CRUD::generate('Product\\Model\\ProductCategory', 'Update');
ActionKit\CRUD::generate('Product\\Model\\Category', 'Create');
ActionKit\CRUD::generate('Product\\Model\\Category', 'Update');
ActionKit\CRUD::generate('Product\\Model\\ProductType', 'Create');
ActionKit\CRUD::generate('Product\\Model\\ProductType', 'Update');

// handle actions
if ( isset($_REQUEST['action']) ) {
    try {
        $container = ActionKit\ServiceContainer::getInstance();
        $runner = new ActionKit\ActionRunner(null, $container);
        $result = $runner->run( $_REQUEST['action'] );
        if ( $result && $runner->isAjax() ) {
            // Deprecated:
            // The text/plain seems work for IE8 (IE8 wraps the 
            // content with a '<pre>' tag.
            header('Cache-Control: no-cache');
            header('Content-Type: text/plain; Charset=utf-8');

            // Since we are using "textContent" instead of "innerHTML" attributes
            // we should output the correct json mime type.
            // header('Content-Type: application/json; Charset=utf-8');
            echo $result->__toString();
            exit(0);
        }
    } catch ( Exception $e ) {
        /**
            * Return 403 status forbidden
            */
        header('HTTP/1.0 403');
        if ( $runner->isAjax() ) {
            die(json_encode(array(
                    'error' => 1,
                    'message' => $e->getMessage()
            )));
        } else {
            die( $e->getMessage() );
        }
    }
}


if ( isset($result) ) {
    var_dump($result->message);
}


$class = ActionKit\CRUD::generate('Product\\Model\\Product', 'Create');
$create = new $class;
echo $create->asView()->render();
