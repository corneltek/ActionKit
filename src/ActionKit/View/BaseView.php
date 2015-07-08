<?php
namespace ActionKit\View;
use FormKit;
use FormKit\Layout\GenericLayout;


/**
 * BaseView create a basic form view for action.
 *
 * To use the BaseView-based action view class:
 *
 *   $view = new YourView($action,array( ... options ... ));
 *
 *
 * A Container
 *   -> A Layout
 *
 */
abstract class BaseView
{

    public $method = 'POST';

    public $enctype = 'multipart/form-data';

    public $container;

    public $layout;

    public $action;

    public $options = array();

    public $fields = array();

    public $_built = false;

    public $renderNested = true;

    abstract public function build();



    /**
     *
     * @param ActionKit\Action $action
     * @param array $options
     */
    public function __construct($action, $options = array() )
    {
        $this->action = $action;
        $this->options = $options;
        if ( isset($options['fields']) ) {
            $this->fields = $options['fields'];
        }
        if ( isset($options['nested']) ) {
            $this->renderNested = $options['nested'];
        }
        $this->init();
    }

    public function init()
    {
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Create layout container
     *
     * @return GenericLayout The layout container object.
     */
    public function createLayout()
    {
        $layout = new GenericLayout;
        // initialize layout object here.
        if ( $width = $this->option('width') ) {
            $layout->width( $width );
        }
        if ( $padding = $this->option('cellpadding') ) {
            $layout->cellpadding( $padding );
        }
        if ( $spacing = $this->option('cellspacing') ) {
            $layout->cellspacing( $spacing );
        }
        if ( $border = $this->option('border') ) {
            $layout->border(0);
        }
        return $layout;
    }



    public function getLayout()
    {
        if ( $this->layout ) {
            return $this->layout;
        }
        return $this->layout = $this->createLayout();
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function createContainer()
    {
        // create default container
        if ( $this->option('no_form') ) {
            $container = new \FormKit\Element\Div;
            return $container;
        } else {
            $container = new \FormKit\Element\Form;

            if ( $this->enctype ) {
                $container->enctype($this->enctype);
            }

            if ( $this->method ) {
                $container->method($this->method);
            }
            if ( $formId = $this->option('form_id') ) {
                $container->setId( $formId );
            }
            if ( $formClass = $this->option('form_class') ) {
                $container->addClass( $formClass );
            }
            return $container;
        }
    }


    public function hasContainer()
    {
        return $this->container !== null;
    }


    /**
     * As we are getting the container object lazily,
     * We need to also append the layout object if the container is 
     * initialized.
     */
    public function getContainer()
    {
        if ( $this->container ) {
            return $this->container;
        }
        $this->container = $this->createContainer();
        if ( ! $this->option('no_layout') ) {
            $this->container->append( $this->getLayout() );
        }
        return $this->container;
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
        if ($this->isRecordAction()) {
            if ($record = $this->action->getRecord()) {
                return $record->id ? true : false;
            }
        }
        return false;
    }

    public function getRecord()
    {
        if ( $this->isRecordAction() ) {
            return $this->action->getRecord();
        }
    }

    /**
     * Use 'fields', 'skips' options to filter widgets for rendering.
     *
     * @return FormKit\Widget\BaseWidget
     */
    public function getAvailableWidgets()
    {
        $widgets = array();
        if ( $fields = $this->option('fields') ) {
            $widgets = $this->action->getWidgetsByNames($fields);
        } else {
            $widgets = $this->action->getWidgets();
        }
        if ( $fields = $this->option('skips') ) {
            $widgets = array_filter($widgets,function($widget) use ($fields) {
                return ! in_array($widget->name,$fields);
            });
        }

        return $widgets;
    }

    /**
     * Register widgets into container object or layout object
     * Hidden fields will be container, visiable fields will be in layout.
     *
     * @param FormKit\Widget\BaseWidget[]
     */
    public function registerWidgets($widgets)
    {
        // push widgets to layout.
        foreach ($widgets as $widget) {
            // put HiddenInput widget out of table,
            // so that we don't have empty cells.
            if ($widget instanceof \FormKit\Widget\HiddenInput) {
                $this->getContainer()->append($widget);
            } else {
                $this->getLayout()->addWidget($widget);
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

    public function getSkipFields()
    {
        return $this->skips;
    }


    /**
     * Set options
     *
     * @param array $options
     */
    public function options($options)
    {
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
        if ( isset($this->options[$key]) ) {
            return $this->options[$key];
        }
    }


    public function __call($method,$args)
    {
        if ( method_exists( $this,'set' . ucfirst($method) ) ) {
            call_user_func_array('set' . ucfirst($method), $args);

            return $this;
        }
        throw new RuntimeException("$method not found.");
    }


    /**
     * A build wrapper method for build().
     *
     * This call beforeBuild, build, afterBuild methods before rendering the 
     * content.
     *
     * @return Container
     */
    public function triggerBuild()
    {
        $this->beforeBuild();
        $this->build();
        $this->afterBuild();

        $this->_built = true;
        return $this->getContainer();
    }

    /**
     * Trigger method before building the content.
     */
    public function beforeBuild() {  }


    /**
     * Trigger method after building the content.
     */
    public function afterBuild() {  }


    public function render()
    {
        if (! $this->_built ) {
            $this->triggerBuild();
        }
        return $this->getContainer()->render();
    }

    public function renderFields($fields)
    {
        $this->fields = $fields;
        return $this->render();
    }

}
