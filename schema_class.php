<?php
use Phifty\AppClassKit;

AppClassKit::loadPluginModels();

$finder = new LazyRecord\Schema\SchemaFinder;
return $finder->getSchemaClasses();
