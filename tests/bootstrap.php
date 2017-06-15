<?php
require 'vendor/autoload.php';

use Maghead\Generator\Schema\SchemaGenerator;
use Maghead\Schema\Loader\ComposerSchemaLoader;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Config\FileConfigLoader;
use Maghead\Runtime\Config\ArrayConfigLoader;
use Maghead\Runtime\Bootstrap;

if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}


/*
$config = ArrayConfigLoader::load([
    'cli' => [ 'bootstrap' => ['tests/bootstrap.php'] ],
    'schema' => [
        'auto_id' => 1,
        'paths' => ['tests'],
    ],
    'databases' => [
        'master' => [
            'dsn' => 'sqlite::memory:',
            'user' => NULL,
            'password' => NULL,
        ],
    ]
]);
Bootstrap::setup($config);
*/

/*
$loader = ComposerSchemaLoader::from('composer.json');
$loader->load();

$logger = new CLIFramework\Logger;
// $logger->setQuiet();
$logger->info("Updating schema class files...");
$schemas = SchemaLoader::loadDeclaredSchemas();
$g = new SchemaGenerator($config, $logger);
$g->setForceUpdate(true);
$g->generate($schemas);
 */

/**
 * Clean up cache files
 */
const CACHE_DIR = 'src/Cache';
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
