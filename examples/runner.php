<?php
require '../vendor/autoload.php';

$container = new ServiceContainer;
$runner = new ActionRunner($container);

if ($result = $runner->handleWith(STDOUT, $_REQUEST)) {
    var_dump( $result ); 
}
