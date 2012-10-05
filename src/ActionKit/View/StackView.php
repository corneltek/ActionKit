<?php
namespace ActionKit\View;
use FormKit;
use FormKit\Layout\FieldsetLayout;
use ActionKit\View\BaseView;
use FormKit\Widget\HiddenInput;

class StackView extends BaseView
{
    public $method = 'POST';
    public $ajax = false;

    public function createLayout()
    {
        $layout = new \FormKit\Layout\GenericLayout;

        // initialize layout object here.
        if( $width = $this->option('width') )
            $layout->width( $width );
        if( $padding = $this->option('cellpadding') )
            $layout->cellpadding( $padding );
        if( $spacing = $this->option('cellspacing') )
            $layout->cellspacing( $spacing );
        if( $border = $this->option('border') )
            $layout->border(0);
        return $layout;
    }


    /**
     * Create Layout Container object.
     */
    public function createContainer()
    {
        if( $this->option('no_form') ) {
            $container = new FormKit\Element\Div;
        } else {
            $container = new FormKit\Element\Form;
            $container->method($this->method);
            if( $formId = $this->option('form_id') ) {
                $container->addId( $formId );
            }
            if( $formClass = $this->option('form_class') ) {
                $container->addClass( $formClass );
            }
            if( $this->ajax ) {
                $ajaxFlag  = new HiddenInput('__ajax_request',array( 'value' => '1' ));
                $container->append( $ajaxFlag );
                $container->addClass('ajax-action');
            }
        }
        return $container;
    }

    public function buildNestedSection($container)
    {
        $record = $this->getRecord();
        $recordId = $record ? $record->id : null;

            foreach( $this->action->relationships as $relationId => $relation ) {
                if( $recordId && isset($record->{ $relationId }) ) {
                    // for each existing records
                    foreach( $record->{ $relationId } as $subrecord ) {
                        $subview = $this->createSubactionView($relationId, $relation, $subrecord);
                        $container->append($subview);
                    }
                } 

                $record = new $relation['record'];
                $subview = $this->createSubactionView($relationId,$relation);
                $html = addslashes($subview->render());
                $button = new \FormKit\Widget\ButtonInput;
                $button->value = _('Add') . $record->getLabel();
                $button->onclick = <<<SCRIPT
                    var self = this;
                    var el = document.createElement('div');
                    var closeBtn = document.createElement('input');
                    closeBtn.type = 'button';
                    closeBtn.value = '移除';
                    closeBtn.onclick = function() {
                        self.parentNode.removeChild(el);
                    };
                    el.innerHTML = '$html';
                    el.appendChild( closeBtn );
                    this.parentNode.insertBefore(el, this.nextSibling);
SCRIPT;
                $container->append($button);
            }
    }

    public function build($container)
    {
        $container->append( $this->layout );

        $widgets = $this->getAvailableWidgets();
        $this->registerWidgets($widgets);


        $record = $this->getRecord();
        $recordId = $record ? $record->id : null;

        // Render relationships if attribute 'nested' is defined.
        if( $this->action->nested ) {
            $this->buildNestedSection($container);
        }

        // if we use form
        if( ! $this->option('no_form') ) {

            // Add control buttons
            $submit = new FormKit\Widget\SubmitInput;
            // $this->layout->addWidget($submit);
            $container->append($submit);

            // if we have record and the record has an id, render the id field as hidden field.
            if( $recordId ) {
                if( $paramId = $this->action->getParam('id') ) {
                    // if id field is defined, and the record exists.
                    if( $recordId && $paramId->value ) {
                        $container->append( new HiddenInput('id',array('value' => $paramId->value )) );
                    }
                }
            }
            if( ! $this->option('no_signature') ) {
                $signature = new HiddenInput('action',array(
                    'value' => $this->action->getSignature()
                ));
                $container->append( $signature );
            }
        }
        return $container;
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
        $formIndex = $action->setParamNamesWithIndex($relationId);
        $subview = new self($action, array(
            'no_form' => 1,
            'ajax' => $this->ajax
        ));
        $subview->triggerBuild();
        $container = $subview->getContainer();
        $signature = new HiddenInput(  "{$relationId}[{$formIndex}][action]",array(
            'value' => $action->getSignature()
        ));
        $container->append( $signature );
        return $subview;
    }

    public function beforeBuild() { }

    public function afterBuild() { }

    /**
     * create container object.
     *
     * trigger beforeBuild, build, afterBuild methods
     */
    public function triggerBuild()
    {
        $this->container = $this->createContainer();
        $this->beforeBuild();
        $this->build($this->container);
        $this->afterBuild();
        return $this->container;
    }

    public function render()
    {
        if(!$this->container) {
            $this->triggerBuild();
        }
        return $this->getContainer()->render();
    }
}

