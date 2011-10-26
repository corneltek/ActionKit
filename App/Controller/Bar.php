<?php

namespace TestApp\Controller;

use Phifty\Controller;

class Bar extends \Phifty\Controller
{
    function run()
    {
        return $this->render('testapp/template/default.html');
    }
}

?>
