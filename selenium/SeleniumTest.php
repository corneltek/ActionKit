<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
 
class SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        # $this->setBrowser('*googlechrome');
        $this->setBrowserUrl('http://www.google.com/');
    }
 
    public function testTitle()
    {
        $this->open('http://www.google.com/');
        $this->assertTitleEquals('Google');
    }
}

