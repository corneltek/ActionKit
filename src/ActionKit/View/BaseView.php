<?php
namespace ActionKit\View;

abstract class BaseView
{

    public $action;

    public $options;

    /**
     *
     * @param ActionKit\Action $action
     */
    function __construct($action, $options = array() ) {
        $this->action = $action;
        $this->options = $options;
        $this->build();
    }

    abstract function build();

    abstract function render();
}





