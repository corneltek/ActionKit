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

/*
$config = ConfigLoader::getInstance();
$config->loadFromArray(array( 
    'bootstrap' => array ('tests/bootstrap.php'),
    'schema' => array(
        'auto_id' => 1,
        'paths' => array('tests'),
    ),
    'data_sources' =>
    array (
        'default' =>
            array (
                'dsn' => 'sqlite::memory:',
                // 'dsn' => 'sqlite:testing.sqlite3',
                'user' => NULL,
                'pass' => NULL,
            ),
        'pgsql' =>
            array (
                'dsn' => 'pgsql:host=localhost;dbname=testing',
                'user' => 'postgres',
            ),
    ),
));

$logger = new Logger;
$logger->info("Building schema class files...");
$schemas = array(
    new \TestApp\Model\UserSchema,
    new \TestApp\Model\IDNumberSchema,
    new \TestApp\Model\NameSchema,
    new \AuthorBooks\Model\AddressSchema,
    new \AuthorBooks\Model\BookSchema,
    new \AuthorBooks\Model\AuthorSchema,
    new \AuthorBooks\Model\AuthorBookSchema,
    new \AuthorBooks\Model\PublisherSchema,
    new \MetricApp\Model\MetricValueSchema,
    new \PageApp\Model\PageSchema,
);
$g = new \LazyRecord\Schema\SchemaGenerator($config, $logger);
$g->setForceUpdate(true);
$g->generate($schemas);
 */



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
