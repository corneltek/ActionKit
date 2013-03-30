<?php

class FooTemplateView extends ActionKit\View\TemplateView
{
    
    public function render()
    {
        return $this->renderTemplateFile('foo.html',array(  ));
    }

}


