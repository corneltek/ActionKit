<?php
use ActionKit\Template;
use ActionKit\StringTemplate;

class TemplateTest extends PHPUnit_Framework_TestCase
{

    public function testStringTemplate() {
        $t = new StringTemplate("{{foo}}");
        ok($t);
        $output = $t->render(array( 'foo' => 1 ));
        ok($output);
        is('1', $output);
    }

    public function testTemplate()
    {
        $t = new Template;
        ok($t);
        ok($t->getClassDir());
    }
}

