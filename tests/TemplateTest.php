<?php
use ActionKit\Template;
use ActionKit\StringTemplate;

class TemplateTest extends \PHPUnit\Framework\TestCase
{

    public function testStringTemplate() {
        $t = new StringTemplate("{{foo}}");
        $this->assertNotNull($t);
        $output = $t->render(array( 'foo' => 1 ));
        $this->assertNotNull($output);
        $this->assertEquals('1', $output);
    }

    public function testTemplate()
    {
        $t = new Template;
        $t->init();
        $this->assertNotNull($t);
        $this->assertNotNull($t->getClassDir());
    }
}

