<?php
namespace Phifty\Assets;

use Phifty\Asset;

class qunit extends Asset 
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
