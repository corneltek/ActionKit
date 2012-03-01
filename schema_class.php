<?php
# require 'autoload.php';

use Phifty\AppClassKit;

AppClassKit::loadCoreModels();
AppClassKit::loadAppModels();
AppClassKit::loadPluginModels();

$finder = new Lazy\Schema\SchemaFinder;
return $finder->getSchemaClasses();
