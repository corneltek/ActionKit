<?php
/*
 * This file is part of the Phifty package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Phifty\Widgets;
use Phifty\Widget;

class Growler extends Widget 
{
    function js()
    {
        return array( 'jgrowl/jquery.jgrowl.js' );
    }

    function css() 
    {
        return array( 'jgrowl/jquery.jgrowl.css' );
    }

}
