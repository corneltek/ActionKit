<?php

namespace TestApp\Action;

use Phifty\Action;

class Dummy extends Action
{
    function schema() 
    {
        $this->param('name');
    }

    function run()
    {
        return $this->success( 'Hello ' . $this->arg('name') );
    }
}



