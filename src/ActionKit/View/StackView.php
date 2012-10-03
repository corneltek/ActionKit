<?php
namespace ActionKit\View;
use FormKit;
use FormKit\Layout\FieldsetLayout;
use ActionKit\View\BaseView;
use FormKit\Widget\HiddenInput;

class StackView extends BaseView
{
    public $layout;
    public $method = 'POST';
    public $ajax = false;

    public function getAvailableWidgets()
    {
        if( $fields = $this->option('fields') ) {
            return $this->action->getWidgetsByNames($fields);
        } else {
            return $this->action->getWidgets();
        }
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

        if( $this->option('no_form') ) {
            $wrapper = new FormKit\Element\Div;
        } else {
            $wrapper = new FormKit\Element\Form;
            $wrapper->method($this->method);
            if( $formId = $this->option('form_id') ) {
                $wrapper->addId( $formId );
            }
            if( $formClass = $this->option('form_class') ) {
                $wrapper->addClass( $formClass );
            }
        }
        $wrapper->append( $this->layout );


        $widgets = $this->getAvailableWidgets();

        // add widgets to layout.
        foreach( $widgets as $widget ) {
            // put HiddenInput widget out of table,
            // so that we don't have empty cells.
            if( $widget instanceof \FormKit\Widget\HiddenInput ) {
                $wrapper->append($widget);
            } else {
                $this->layout->addWidget($widget);
            }
        }

        $hasRecord   = isset($this->action->record);
        $hasRecordId = isset($this->action->record) && $this->action->record->id;

        /**
         * Render relationships if attribute 'nested' is defined.
         */
        if( $this->action->nested ) {
            foreach( $this->action->relationships as $relationKey => $relation ) {
                if( $hasRecordId ) {
                    // for each existing records
                    foreach( $this->action->record->{ $relationKey } as $subrecord ) {
                        $subview = $this->createSubactionView($relationKey, $relation, $record);

                    }
                } else {
                    $subview = $this->createSubactionView($relationKey,$relation);
                    $wrapper->append($subview);
                }
            }
        }

        // if we use form
        if( ! $this->option('no_form') ) {

            // Add control buttons
            $submit = new FormKit\Widget\SubmitInput;
            // $this->layout->addWidget($submit);
            $wrapper->append($submit);
            if( $this->ajax ) {
                $ajaxFlag  = new HiddenInput('__ajax_request',array( 'value' => '1' ));
                $wrapper->append( $ajaxFlag );
                $wrapper->addClass('ajax-action');
            }

            // if we have record and the record has an id, render the id field as hidden field.
            if( $hasRecordId ) {
                if( $paramId = $this->action->param('id') ) {
                    $recordId = $this->action->record->id;

                    // if id field is defined, and the record exists.
                    if( $recordId && $paramId->value ) {
                        $wrapper->append( new HiddenInput('id',array('value' => $paramId->value )) );
                    }
                }
            }
            $signature = new HiddenInput('action',array(
                'value' => $this->action->getSignature()
            ));
            $wrapper->append( $signature );
        }
        return $wrapper;
    }

    public function createSubactionView($relationId,$relation, $record = null)
    {
        if( ! $record ) {
            $recordClass = $relation['record'];
            $record      = new $recordClass;
            $action      = $record->asCreateAction();
        } else {
            $action      = $record->asUpdateAction();
        }
        $action->setParamNamesWithIndex($relationId);
        $subview = new self($action, array(
            'no_form' => 1,
            'ajax' => $this->ajax
        ));
        return $subview;
    }

    public function render()
    {
        $wrapper = $this->build();
        return $wrapper->render();
    }
}

