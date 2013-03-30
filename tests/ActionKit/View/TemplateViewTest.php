<?php

class TemplateViewTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $view = new FooTemplateView;
        ok($view);
    }
}

