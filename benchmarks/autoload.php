<?php
$taskName = 'autoload';

$t1 = microtime(true);
require 'autoload.php';
$t2 = (microtime(true) - $t1);
echo $t2 * 1000 . " milliseconds.\n";
echo $t2 . " seconds.\n";

$m = new Mongo;
$db = $m->benchmarks;
// select a collection (analogous to a relational database's table)
$collection = $db->phifty;

$commit = system('git rev-parse HEAD');

echo "git commit:" . $commit . "\n";

#  // add a record
$obj = array( 'task' => $taskName , 'duration' => $t2 * 1000 , 'unit' => 'ms' , 'commit' => $commit , 'created_on' => new MongoDate(time()) ); // microseconds
$collection->insert($obj);
