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

    function option($key) {
        if( isset($this->options[$key]) ) {
            return $this->options[$key];
        }
    }

    abstract function build();

    abstract function render();
}





