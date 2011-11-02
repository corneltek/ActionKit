#!/usr/bin/env php
<?php
require 'autoload.php';

use DMenu\MenuBuilder;
$builder = new MenuBuilder;
$builder->addExpander( 'products' , '\DMenu\ProductMenuExpander' );
echo $builder->build();

# echo $builder->build();
