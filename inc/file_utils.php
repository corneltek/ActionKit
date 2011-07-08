<?php



function path_join()
{
    $args = func_get_args();
    return call_user_func(  'join' , DIRECTORY_SEPARATOR , $args );
}


?>
