<?php
namespace Phifty\Widgets;

use Phifty\Widget;

class QUnit extends Widget 
{

    public function js()
    {
        return array('qunit/qunit/qunit.js');
    }

    public function css()
    {
        return array('qunit/qunit/qunit.css');
    }
}
