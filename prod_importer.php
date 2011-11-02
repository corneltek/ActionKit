#!/usr/bin/env php
<?php
require 'autoload.php';

// Changes
//
// alter table products change column sn sn varchar(512);
// alter table products change column name name varchar(256);
// alter table products change column subtitle subtitle varchar(512);
//
use Product\ProductImporter;

$importer = new ProductImporter;
$importer->setImageBase( 'static/products' );
$importer->setWebroot( 'webroot' );
$importer->import( 'prod' );
