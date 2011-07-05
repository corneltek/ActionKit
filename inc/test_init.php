<?php
define( 'PH_ROOT' , __DIR__ );
define( 'PH_ENVIRONMENT' , 'dev' ); // or 'prod'
define( 'PH_DEBUG', false);
define( 'PH_APP_NAME', 'Test' );
require_once( 'autoload.php' );

/* test core functions */
global $test_cnt;
global $failed_cnt;

$test_cnt = 0;

function ok( $value , $message = null ) {
	global $test_cnt;
	echo ++$test_cnt . " ";

	echo $value ? "ok" : "fail";
	if( $message )
		echo " - " . $message;
    elseif ( is_numeric( $value ) || is_string( $value ) )
        echo " - got " . $value;

    global $failed_cnt;
    if( ! $value )
        $failed_cnt++;

	echo "\n";
    return $value;
}

function is( $arg1, $arg2, $message = null )  {
	global $test_cnt;
	echo ++$test_cnt . " ";

	$ret = $arg1 == $arg2;
	echo $ret ? "ok" : "fail";
	if( $message )
		echo " - " . $message;
    elseif ( is_numeric( $arg1 ) || is_string( $arg1 ) )
        echo " - got " . $arg1;

	echo "\n";

	if( !$ret ) {
		echo "\nERROR\n";
		echo "   expecting:" . $arg1 . "\n";
		echo "   to be:" . $arg2 . "\n";
        global $failed_cnt;
        $failed_cnt++;
	}
    return $ret;
}

function done( $num )
{
    global $failed_cnt;
	global $test_cnt;

    if( $failed_cnt > 0 ) {
        echo "TEST FAILED\n";
        echo ($num - $failed_cnt) . " / $num\n";
        exit(0);
    }

	if( $num == $test_cnt ) {
		echo "Tests done!\n";
	} else {
		echo "TEST NUMBER IS NOT MATCH!!! FAILED.\n";
        echo "Expecting $num , but tested $test_cnt\n";
	}
}

