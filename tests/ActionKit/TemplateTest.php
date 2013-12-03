<?php
use ActionKit\Template;

class TemplateTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $t = new Template;
        ok($t);
    }
}

