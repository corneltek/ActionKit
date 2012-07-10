<?php
namespace ActionKit\View;
use FormKit;
use FormKit\Layout\FieldsetLayout;
use ActionKit\View\BaseView;
use FormKit\Widget\HiddenInput;

class StackView extends BaseView
{
    public $layout;
    public $form;
    public $method = 'POST';
    public $ajax = false;



    /**
     * Get Widgets from action
     */
    public function getActionWidgets($action)
    {
        $widgets = array();
        // for each widget, push it into stack
        foreach( $action->params as $param ) {
            // we ignore id column, 
            // because we need to render the id field with 
            // HiddenInput manually.
            if( 'id' === $param->name) {
                continue;
            }

            if( $action->filterOutFields && in_array($param->name,$action->filterOutFields) ) {
                continue;
            }

            if( $this->fields && ! in_array($param->name,$this->fields) ) {
                continue;
            }

            $widgets[] = $param->createWidget();
        }
        return $widgets;
    }

    public function build()
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

        $form = new FormKit\Element\Form;
        $form->method($this->method);

        $widgets = $this->getActionWidgets( $this->action );

        // add widgets to layout.
        foreach( $widgets as $widget ) {
            if( is_a($widget,'FormKit\Widget\HiddenInput') ) {
                $form->append($widget);
            } else {
                $this->layout->addWidget($widget);
            }
        }

        // Add control buttons
        $submit = new FormKit\Widget\SubmitInput;
        $this->layout->addWidget($submit);


        if( $this->ajax ) {
            $ajaxFlag  = new HiddenInput('__ajax_request',array( 'value' => '1' ));
            $form->append( $ajaxFlag );
        }

        $hasRecord   = isset($this->action->record);
        $hasRecordId = isset($this->action->record) && $this->action->record->id;

        // if we have record and the record has an id, render the id field as hidden field.
        if( $hasRecordId ) {
            if( $paramId = $this->action->param('id') ) {
                $recordId = $this->action->record->id;

                // if id field is defined, and the record exists.
                if( $recordId && $paramId->value ) {
                    $form->append( new HiddenInput('id',array('value' => $paramId->value )) );
                }
            }
        }

        $signature = new HiddenInput('action',array(
            'value' => $this->action->getSignature()
        ));
        
        $form->append( $signature );
        $form->append( $this->layout );
        $this->form = $form;
    }

    public function render() 
    {
        $this->build();
        return $this->form->render();
    }
}

