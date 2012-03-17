<?php
namespace Core\Controller;
use Phifty\Controller;
class Index extends Controller
{
    function run()
    {
        return $this->render( 'Core/template/index.html' );
    }

}
