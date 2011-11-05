<?php
namespace TestApp\Controller;

use Phifty\Controller;

class ActionUnitTest extends \Phifty\Controller
{
    function run()
    {
        return $this->render('TestApp/template/tests_action.html');
    }
}

