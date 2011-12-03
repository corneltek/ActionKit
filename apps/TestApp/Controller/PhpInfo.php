<?php
namespace TestApp\Controller;

use Phifty\Controller;

class PhpInfo extends \Phifty\Controller
{
    function run()
    {
        ob_start();
        phpinfo();
        $content = ob_get_contents();
        ob_clean();
        return $content;
    }
}

