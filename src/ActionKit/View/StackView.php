<?php
namespace ActionKit\View;
use FormKit;

/**
 * Action View Synopsis
 *
 *      $action =  ....
 *      $view = new ActionKit\View\StackView($action, $options );
 *      $view->render();
 */

class StackView extends BaseView
{
    public $layout;



    function option($key) {
        if( isset($this->options[$key]) ) {
            return $this->options[$key];
        }
    }

    function build()
    {
        $this->layout = new FormKit\Layout\GenericLayout;
        if( $width = $this->option('width') ) {
            $layout->width( $width );
        }
        if( $padding = $this->option('cellpadding') ) {
            $layout->cellpadding( $padding );
        }
        if( $spacing = $this->option('cellspacing') ) {
            $layout->cellspacing( $spacing );
        }
        if( $border = $this->option('border') ) {
            $layout->border(0);
        }

        // for each widget, push it into stack
        foreach( $this->action->params as $param ) {
            $widget = $param->createWidget();
            $layout->addWidget( $widget );
        }
    }

    function render() 
    {
        $form = new FormKit\Element\Form;
        $form->method('post');
        $form->addChild( $layout );
        return $form->render();
    }

}



