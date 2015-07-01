<?php
define('ROOT' , dirname(__DIR__) );
$loader = require ROOT . '/vendor/autoload.php';
$loader->add(null, ROOT . '/tests');

function __() 
{
    $args = func_get_args();
    $msg = _( array_shift( $args ) );
    $id = 1;
    foreach ($args as $arg) {
        $msg = str_replace( "%$id" , $arg , $msg );
        $id++;
    }
    return $msg;
}


/**
 * Clean up cache files
 */
futil_rmtree('src/ActionKit/Cache');
mkdir('src/ActionKit/Cache', 0755, true);

use WebServerRunner\WebServerRunner;
if (defined('WEB_SERVER_HOST') && defined('WEB_SERVER_PORT')) {
    $runner = new WebServerRunner(WEB_SERVER_HOST, WEB_SERVER_PORT, WEB_SERVER_DOCROOT);
    $runner->setVerbose(true);
    $runner->execute();
    $runner->stopOnShutdown();
}

