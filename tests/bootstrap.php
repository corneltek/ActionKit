<?php
use LazyRecord\Schema\SchemaGenerator;
use LazyRecord\ConfigLoader;

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

$config = ConfigLoader::getInstance();
$config->loadFromArray(array(
    'bootstrap' => ['tests/bootstrap.php'],
    'schema' => [
        'auto_id' => 1,
        'paths' => ['tests'],
    ],
    'data_sources' => [
        'default' => [
                'dsn' => 'sqlite::memory:',
                'user' => NULL,
                'pass' => NULL,
        ],
    ],
));





$logger = new CLIFramework\Logger;
// $logger->setQuiet();
$logger->info("Updating schema class files...");
$schemas = array(
    new \User\Model\UserSchema,
    new \OrderBundle\Model\OrderSchema,
    new \OrderBundle\Model\OrderItemSchema,
);
$g = new \LazyRecord\Schema\SchemaGenerator($config, $logger);
$g->setForceUpdate(true);
$g->generate($schemas);



/**
 * Clean up cache files
 */
const CACHE_DIR = 'src/ActionKit/Cache';
const UPLOAD_DIR = 'tests/upload';
if (file_exists(CACHE_DIR)) {
    futil_rmtree(CACHE_DIR);
    mkdir(CACHE_DIR, 0755, true);
} else {
    mkdir(CACHE_DIR, 0755, true);
}

if (file_exists(UPLOAD_DIR)) {
    futil_rmtree(UPLOAD_DIR);
    mkdir(UPLOAD_DIR, 0755, true);
} else {
    mkdir(UPLOAD_DIR, 0755, true);
}

/*
use WebServerRunner\WebServerRunner;
if (defined('WEB_SERVER_HOST') && defined('WEB_SERVER_PORT')) {
    $runner = new WebServerRunner(WEB_SERVER_HOST, WEB_SERVER_PORT, WEB_SERVER_DOCROOT);
    $runner->setVerbose(true);
    $runner->execute();
    $runner->stopOnShutdown();
}
 */
