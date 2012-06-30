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

        // for each widget, push it into stack
        foreach( $this->action->params as $param ) {
            if( 'id' === $param->name) {
                continue;
            }

            if( in_array($param->name,$this->action->filterOutFields) ) {
                continue;
            }

            if( $this->fields && ! in_array($param->name,$this->fields) ) {
                continue;
            }
            $this->layout->addWidget( $param->createWidget() );
        }

        // Add control buttons
        $submit = new FormKit\Widget\SubmitInput;
        $this->layout->addWidget($submit);

        $form = new FormKit\Element\Form;
        $form->method($this->method);

        if( $this->ajax ) {
            $ajaxFlag  = new HiddenInput('__ajax_request',array( 'value' => '1' ));
            $form->addChild( $ajaxFlag );
        }

        $hasRecord   = isset($this->action->record);
        $hasRecordId = isset($this->action->record) && $this->action->record->id;

        if( $hasRecordId ) {
            if( $paramId = $this->action->param('id') ) {
                $recordId = $this->action->record->id;

                // if id field is defined, and the record exists.
                if( $recordId && $paramId->value ) {
                    $hiddenInput = new HiddenInput('id',array('value' => $paramId->value ));
                    $form->addChild($hiddenInput);
                }
            }
        }

        $signature = new HiddenInput('action',array(
            'value' => $this->action->getSignature()
        ));
        
        $form->addChild( $signature );
        $form->addChild( $this->layout );
        $this->form = $form;
    }

    public function render() 
    {
        $this->build();
        return $this->form->render();
    }
}

