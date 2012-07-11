<?php
namespace ActionKit\View;

abstract class BaseView
{

    public $action;

    public $options = array();

    public $fields;
    /**
     *
     * @param ActionKit\Action $action
     */
    function __construct($action, $options = array() ) {
        $this->action = $action;
        $this->options = $options;
        if( isset($options['fields']) ) {
            $this->fields = $options['fields'];
        }
    }

    public function fields($fields) {
        $this->fields = $fields;
        return $this;
    }

    public function action($action) { 
        $this->action = $action;
        return $this;
    }

    public function options($options) {
        $this->options = $options;
        return $this;
    }

    public function option($key) {
        if( isset($this->options[$key]) ) {
            return $this->options[$key];
        }
    }

    abstract function build();
    abstract function render();
}





