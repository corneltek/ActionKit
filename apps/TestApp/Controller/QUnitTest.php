<?php
namespace TestApp\Controller;

use Phifty\Controller;

class QUnitTest extends \Phifty\Controller
{
    function run()
    {
        return $this->render('widgets/QUnit/template/index.html');
    }
}

