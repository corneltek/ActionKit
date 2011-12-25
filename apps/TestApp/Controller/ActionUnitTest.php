<?php
namespace TestApp\Controller;

use Phifty\Controller;
use Phifty\AssetLoader;

class ActionUnitTest extends \Phifty\Controller
{
    function run()
    {
        $w = AssetLoader::load('QUnit');
        return $this->render('TestApp/template/tests_action.html');
    }
}

