<?php

namespace TestApp\Controller;

use Phifty\Controller;

class Bar extends \Phifty\Controller
{
    function run()
    {
        $this->render('default.html');
    }
}

?>
