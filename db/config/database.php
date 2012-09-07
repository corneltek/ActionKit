<?php return array (
  'bootstrap' => 
  array (
    0 => 'main.php',
  ),
  'schema' => 
  array (
    'auto_id' => true,
    'loader' => 'src/Phifty/SchemaLoader.php',
    'base_model' => '\\Phifty\\Model',
    'base_collection' => '\\Phifty\\Collection',
  ),
  'seeds' => 
  array (
    0 => 'User::Seed',
  ),
  'data_sources' => 
  array (
    'default' => 
    array (
      'driver' => 'mysql',
      'host' => 'localhost',
      'database' => 'phifty',
      'user' => 'root',
      'pass' => 123123,
    ),
  ),
);