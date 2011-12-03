<?php
namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\WidgetLoader;

class ActionUnitTest extends \Phifty\Controller
{
    function run()
    {
        $w = WidgetLoader::load('QUnit');
        return $this->render('TestApp/template/tests_action.html');
    }
}

