<?php
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




// PHP server runner
$command = sprintf(
    'php -S %s:%d -t %s >/dev/null 2>&1 & echo $!',
    WEB_SERVER_HOST,
    WEB_SERVER_PORT,
    WEB_SERVER_DOCROOT
);
 
// Execute the command and store the process ID
$output = array(); 
exec($command, $output);
$pid = (int) $output[0];
 
echo sprintf(
    '%s - Web server started on %s:%d with PID %d', 
    date('r'),
    WEB_SERVER_HOST, 
    WEB_SERVER_PORT, 
    $pid
) . PHP_EOL;
 
// Kill the web server when the process ends
register_shutdown_function(function() use ($pid) {
    echo sprintf('%s - Killing process with ID %d', date('r'), $pid) . PHP_EOL;
    exec('kill ' . $pid);
});
