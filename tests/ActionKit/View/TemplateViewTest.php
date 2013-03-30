<?php

class TemplateViewTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $view = new ActionKit\View\TemplateView;
        ok($view);
    }
}

