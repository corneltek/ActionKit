<?php
use Phifty\AppClassKit;

AppClassKit::loadCoreModels();
AppClassKit::loadAppModels();
AppClassKit::loadPluginModels();

$finder = new LazyRecord\Schema\SchemaFinder;
return $finder->getSchemaClasses();
