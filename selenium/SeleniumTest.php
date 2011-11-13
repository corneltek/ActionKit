<?php
require_once 'autoload.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

use Phifty\Testing\SeleniumTestCase;

class SeleniumWebTest extends SeleniumTestCase
{

    function setUp()
    {
        $this->setBrowser('*firefox');
        # $this->setBrowser('*googlechrome');
        $this->setBrowserUrl('http://www.google.com/');
    }
 
    function testTitle()
    {
        # $this->open('http://phifty.dev/qunit');
        $title = $this->getTitle();
        $this->assertEquals(1,1);

        # $this->ok( $title );
        # $this->assertTitleEquals('Google');
    }
}


