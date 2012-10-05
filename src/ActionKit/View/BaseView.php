<?php
namespace ActionKit\View;

abstract class BaseView
{
    public $container;

    public $layout;

    public $action;

    public $options = array();

    public $fields;

    abstract function build($container);

    abstract function render();

    abstract function createLayout();


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

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function hasContainer()
    {
        return isset($this->container);
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
        return $this->action instanceof \ActionKit\RecordAction\BaseRecordAction;
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

    public function hasRecord()
    {
        return $this->isRecordAction() 
            && $this->action->record->id;
    }

    public function getRecord()
    {
        if( $this->isRecordAction() ) {
            return $this->action->record;
        }
    }

    public function getAvailableWidgets()
    {
        if( $fields = $this->option('fields') ) {
            return $this->action->getWidgetsByNames($fields);
        } else {
            return $this->action->getWidgets();
        }
    }


    /**
     * Register widgets into container object, layout object.
     *
     * @param array Widget
     */
    public function registerWidgets($widgets)
    {
        // push widgets to layout.
        foreach( $widgets as $widget ) {
            // put HiddenInput widget out of table,
            // so that we don't have empty cells.
            if( $widget instanceof \FormKit\Widget\HiddenInput ) {
                $this->container->append($widget);
            } else {
                $this->layout->addWidget($widget);
            }
        }
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


    public function __call($method,$args) 
    {
        if( method_exists( $this,'set' . ucfirst($method) ) ) {
            call_user_func_array('set' . ucfirst($method), $args);
            return $this;
        }
        throw new RuntimeException("$method not found.");
    }
}


