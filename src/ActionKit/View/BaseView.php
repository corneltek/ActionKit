<?php
namespace ActionKit\View;

class BaseView
{

    public $action;

    /**
     *
     * @param ActionKit\Action $action
     */
    function __construct($action) {
        $this->action = $action;
    }

    abstract function build();

    abstract function render();

}





