<?php
namespace ActionKit\FieldView;
use FormKit;
use FormKit\Element\Div;
use FormKit\Element\Span;
use FormKit\Widget\HiddenInput;
use FormKit\Widget\CheckboxInput;

class BootstrapFieldView
{

    /**
     * @var ActionKit\Param object
     */
    public $column;

    public $wrapperClass = 'form-group';

    public $labelClass = 'control-label col-lg-2';

    public $inputWrapperClass = 'col-lg-10';

    public $hintClass = 'hint';

    public $container;

    public $widgetAttributes = array();

    public function __construct($column, $options = array())
    {
        $this->column = $column;

        if (isset($options['wrapperClass'])) 
            $this->wrapperClass = $options['wrapperClass'];

        if (isset($options['labelClass'])) 
            $this->labelClass = $options['labelClass'];

        if (isset($options['inputWrapperClass'])) 
            $this->inputWrapperClass = $options['inputWrapperClass'];

    }

    public function setWidgetAttributes($attrs)
    {
        $this->widgetAttributes = $attrs;
    }

    /**
     *
     * Build a div structure like this:

            <div class="form-group">
                <label for="inputAccount" class="col-lg-2 control-label">* 帳號</label>
                <div class="col-lg-10">
                    <input id="inputAccount" type="text" name="account" placeholder="ariel123" readoly="" autocomplete="off" class="form-control">
                </div>
            </div>
     */
    public function build()
    {
        $wrapper = new Div(array(
            'class' => $this->wrapperClass,
        ));

        $widget = $this->column->createWidget(null, $this->widgetAttributes);
        $widget->addClass('form-control');
        $widgetId = $widget->getSerialId();

        $wrapper->addClass($widget->convertClassToCssClassName());

        /*
        if ( $widget instanceof CheckboxInput ) {
            $label = $this->column->createLabelWidget();
            $label->prepend($widget);
            $wrapper->append($label);
        */
        if ($widget instanceof HiddenInput) {

            $wrapper->append( $widget );

        } else {

            $inputDiv = new Div(array('class' => $this->inputWrapperClass));
            $inputDiv->append($widget);

            $label = $this->column->createLabelWidget();
            $label->setAttributes(array('class' => $this->labelClass, 'for' => $widgetId));

            $wrapper->append($label);
            $wrapper->append($inputDiv);

            if ($this->column->hint) {
                $hintEl  = new Span(array( 'class' => $this->hintClass ));
                $hintEl->append( $this->column->hint );
                $wrapper->append($hintEl);
            }
        }
        return $wrapper;
    }

    public function render()
    {
        return $this->build()->render();
    }
}
