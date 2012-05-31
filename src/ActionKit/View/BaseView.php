<?php
namespace ActionKit\View;

class BaseView
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
    }

    abstract function build();

    abstract function render();

}





