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


    public function getLayout()
    {
        return $this->layout;
    }

    public function getAction()
    {
        return $this->action;
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
    public function fields($fields) 
    {
        $this->fields = $fields;
        return $this;
    }


    /**
     * Set action object
     *
     * @param ActionKit\Action
     */
    public function action($action) { 
        $this->action = $action;
        return $this;
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
}





