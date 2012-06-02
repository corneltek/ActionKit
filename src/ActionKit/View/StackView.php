<?php
namespace ActionKit\View;
use FormKit;
use ActionKit\View\BaseView;

/**
 * Action View Synopsis
 *
 *      $action =  ....
 *      $view = new ActionKit\View\StackView($action, $options );
 *      $view->render();
 *
 *
 * Example:
 *
 *      $action = new User\Action\ChangePassword;
 *      $view = new ActionKit\View\StackView( $action );
 *      echo $view->render();
 *
 * Or you can render action view via Action's `asView` method:
 *
 *      echo $action->asView('ActionKit\View\StackView')->render();
 *
 */
class StackView extends BaseView
{
    public $layout;
    public $form;

    function build()
    {
        // Use Generic Table Layout
        $this->layout = new FormKit\Layout\GenericLayout;
        if( $width = $this->option('width') ) {
            $this->layout->width( $width );
        }
        if( $padding = $this->option('cellpadding') ) {
            $this->layout->cellpadding( $padding );
        }
        if( $spacing = $this->option('cellspacing') ) {
            $this->layout->cellspacing( $spacing );
        }
        if( $border = $this->option('border') ) {
            $this->layout->border(0);
        }

        // for each widget, push it into stack
        foreach( $this->action->params as $param ) {
            if( 'id' === $param->name ) {
                continue;
            }
            $widget = $param->createWidget();
            $this->layout->addWidget( $widget );
        }

        $submit = new FormKit\Widget\SubmitInput;
        $this->layout->addWidget($submit);

        $form = new FormKit\Element\Form;
        $form->method('post');

        $id = $this->action->param('id');
        $hiddenId  = new FormKit\Widget\HiddenInput('id',array( 
            'value' => $id,
        ));

        $signature = new FormKit\Widget\HiddenInput('action',array(
            'value' => $this->action->getSignature()
        ));
        
        $form->addChild( $signature );
        $form->addChild( $hiddenId );
        $form->addChild( $this->layout );
        $this->form = $form;
    }

    function render() 
    {
        return $form->render();
    }
}


