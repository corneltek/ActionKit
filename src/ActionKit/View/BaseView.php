<?php
namespace ActionKit\View;

abstract class BaseView
{
    public $layout;

    public $action;

    public $options = array();

    public $fields;
    /**
     *
     * @param ActionKit\Action $action
     */
    public function __construct($action, $options = array() ) 
    {
        $this->action = $action;
        $this->options = $options;
        if( isset($options['fields']) ) {
            $this->fields = $options['fields'];
        }
        $this->init();
    }

    public function init()
    {
        $this->layout = $this->createLayout();
    }


    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set action object
     *
     * @param ActionKit\Action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function isRecordAction()
    {
        return $this->action instanceof \ActionKit\RecordAction\BaseRecordAction
            || $this->action instanceof \ActionKit\RecordAction\BulkRecordAction
            ;
    }

    /**
     * Set action fields for rendering
     *
     * @param array $fields field names
     */
    public function setFields($fields) 
    {
        $this->fields = $fields;
    }


    /**
     * Return rendered fields.
     */
    public function getFields()
    {
        return $this->fields;
    }


    /**
     * Set options
     *
     * @param array $options
     */
    public function options($options) {
        $this->options = $options;
        return $this;
    }


    /**
     * Get option value
     *
     * @param string $key
     */
    public function option($key) 
    {
        if( isset($this->options[$key]) ) {
            return $this->options[$key];
        }
    }

    abstract function build();
    abstract function render();
    abstract function createLayout();

    public function __call($method,$args) 
    {
        if( method_exists( $this,'set' . ucfirst($method) ) ) {
            call_user_func_array('set' . ucfirst($method), $args);
            return $this;
        }
        throw new RuntimeException("$method not found.");
    }
}


