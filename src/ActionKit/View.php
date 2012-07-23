<?php
namespace ActionKit;
define('NL',"\n");

/**
 * A Generic Action View Generator 
 *
 *    $aView = new Phifty\View\Action( 'UpdateUser' );
 *    $aView->renderFormStart();
 *    $aView->renderField( 'name' );
 *    $aView->renderField( 'name' );
 *    $aView->renderFormEnd();
 *
 *    => integrated by 
 *
 *      $aView->render();
 *
 * */
class View
{
    public $actionName;
    public $actionClass;
    public $action;

    function __construct( $actionClass ) 
    {
        $this->actionClass = $actionClass;
        // $this->actionClass = kernel()->config->('name') . '\Action\\' . $actionName;
        // $this->action = new $this->actionClass;
    }


    function formStart($attrs = "")
    {
        echo '<form method="POST" ' . $attrs . '>' . NL;
        echo '<input type="hidden" name="action" value="' . $this->actionName . '">' . NL;
    }

    function formEnd()
    {
        echo NL . '</form>' . NL;
    }

    function field( $fieldName )
    {
        // get action field
        $field = $this->getActionField();
        $type = $field->getType();

        $widget = $field->getWidget();
        $widget->render();
    }

    function render()
    {
        # echo $this->action->getName();
        $this->formStart();

        $this->formEnd();
    }
}

